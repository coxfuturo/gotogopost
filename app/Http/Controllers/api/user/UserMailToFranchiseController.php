<?php



namespace App\Http\Controllers\api\user;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;

use App\Models\PickupDetails;

use App\Models\Mail;
use App\Models\Franchise;

use App\Models\MailAttachment;

use App\Models\Recipient;
use App\Models\E2HTrackOrder;

use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Config;

use DB;
use Illuminate\Support\Facades\Validator;
use  App\Http\Controllers\franchise\RateCalculator;
use Smalot\PdfParser\Parser;
use Carbon\Carbon;


class UserMailToFranchiseController extends Controller

{


    public function sentMails(Request $request)
    {
        $service_type = $request->service_type;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $sentMails = Mail::where('franchise_id', Franchise::getFranchiseId())
            ->where('service_type', $service_type)
            ->with(['recipients', 'attachments'])
            ->orderBy('created_at', 'desc')
            ->whereDate('created_at', $date)
            ->get()
            ->map(function ($mail) {
                // Franchise details if present
                $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;

                // User details if present
                $mail->user = $mail->user_id != 0 ? $mail->user : null;

                // Calculate total payment amount from attachments
                $cost = $mail->attachments->sum(function ($attachment) {
                    return (float) $attachment->payment_amount;
                });
                $mail->total_payment_amount = number_format($cost, 2);

                // Add destination franchise details for each recipient
                $mail->recipients = $mail->recipients->map(function ($recipient) {
                    // Fetch destination franchise details based on destination_franchise_id
                    if ($recipient->destination_franchise_id) {
                        $recipient->destinationFranchiseDetails = Franchise::find($recipient->destination_franchise_id);
                    } else {
                        $recipient->destinationFranchiseDetails = null;
                    }
                    return $recipient;
                });

                return $mail;
            });


        return view('franchise.mailToFranchise.sentMails', ['sentMails' => $sentMails]);
    }


    public function getPrice(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'files.*' => 'required|mimes:pdf'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => "Only Pdf files are allowed", "showMessage" => 1], 422);
            }

            $total_payment_amount = 0;
            $file_info = [];

            // Check if files are uploaded
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $filePath = $file->getRealPath(); // Get the temporary file path

                        // Initialize PDF parser
                        $parser = new Parser();
                        $pdf = $parser->parseFile($filePath); // Parse the PDF file

                        // Get the number of pages in the PDF
                        $pages = count($pdf->getPages());

                        if ($pages > 0) {
                            // Calculate the cost at 3 rupees per page
                            $rateCalculator = new RateCalculator();
                            $payment_amount = $rateCalculator->calculateShippingCostForE2h($pages, Mail::E2H);
                            $total_payment_amount += $payment_amount;
                            $file_info[] = [
                                'file_name' => $originalName,
                                'file_size' => $file->getSize(), // Use size from the uploaded file
                                'pages' => $pages,
                                'cost' => $payment_amount
                            ];
                        } else {
                            // Handle error if page count could not be retrieved
                            return response()->json(['status' => false, 'message' => 'Could not retrieve page count for ' . $originalName]);
                        }
                    } catch (\Exception $e) {
                        // Handle exception if something goes wrong during the file upload or parsing process
                        return response()->json(['status' => false, 'message' => 'File parsing error: ' . $e->getMessage()]);
                    }
                }
            }

            if ($total_payment_amount < 1) {
                $total_payment_amount = 1;
            }

            return response()->json([
                'status' => true,
                'file_info' => $file_info,
                'total_payment_amount' => number_format($total_payment_amount, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {


        $userId = Auth::guard('apiuser')->user()->id;
        // $user = Auth::guard('apiuser')->user();

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'files.*' => 'required|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        try {

            // Store the mail details
            $mail = Mail::create([
                'user_id' => $userId,
                'mail_code' => Mail::getUniqueCode(),
                'service_type' => 'mail_to_franchise_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // Create tracking order
            E2HTrackOrder::create([
                'mail_id' => $mail->id,
                'mail_code' => $mail->mail_code,
                'user_id' => $userId,
                'destination_franchise_id' => $request->franchise_id,
            ]);

            Recipient::create([
                'mail_id' => $mail->id,
                'recipient_name' => $request->name,
                'recipient_phone' => $request->phone,
                'recipient_email' => $request->email,
                'recipient_address' => $request->address,
                'destination_franchise_id' => $request->franchise_id,
            ]);

            $attachments = [];
            $payment_amount = 0;

            // // Store attachments
            if ($request->hasFile('files')) {

                foreach ($request->file('files') as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $newFileName = time() . '_' . $originalName;
                        $destinationPath = public_path('franchise/mailtofranchise/attachments/');
                        $file->move($destinationPath, $newFileName);
                        $filePath = $destinationPath . $newFileName;
                        $fileSize = filesize($filePath);
                        // Initialize PDF parser
                        $parser = new Parser();
                        $pdf = $parser->parseFile($filePath);
                        // Get the number of pages in the PDF
                        $pages = count($pdf->getPages());

                        if ($pages > 0) {
                            // Calculate the cost at 3 rupees per page
                            $rateCalculator = new RateCalculator;
                            $payment_amount = $rateCalculator->calculateShippingCostForE2h($pages, Mail::E2H);

                            $attachments[] = [
                                'mail_id' => $mail->id,
                                'file_path' => 'franchise/mailtofranchise/attachments/' . $newFileName,
                                'file_name' => $originalName,
                                'file_size' => $fileSize,
                                'payment_amount' => $payment_amount,
                                'pages' => $pages,
                            ];
                        } else {
                            // Handle error if page count could not be retrieved
                            return response()->json(['status' => false, 'message' => 'Could not retrieve page count for ' . $originalName]);
                        }
                    } catch (\Exception $e) {
                        // Handle exception if something goes wrong during the file upload or parsing process
                        return response()->json(['status' => false, 'message' => 'File upload or parsing error: ' . $e->getMessage()]);
                    }
                }
                MailAttachment::insert($attachments);
            }

            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function attachmentReportE2H(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $userId = Auth::guard('apiuser')->user()->id;
        $mail_to_franchise_single = "mail_to_franchise_single";

        $sentMails = Mail::where('user_id', $userId)
            ->where(function ($query) use ($mail_to_franchise_single) {
                $query->where('service_type', $mail_to_franchise_single);
            })
            ->with([
                'recipients' => function ($query) {
                    $query->select('id', 'mail_id', 'recipient_name', 'recipient_phone', 'recipient_email', 'view_date')
                        ->whereNotNull('view_date'); // Filter recipients where view_date is not null
                },
                'attachments:id,mail_id,file_name,file_path'
            ])
            ->orderBy('created_at', 'desc')
            ->get();



        $formattedData = [];

        foreach ($sentMails as $mail) {
            foreach ($mail->recipients as $recipient) {
                $formattedData[] = [
                    'recipient_name' => $recipient->recipient_name ?? 'N/A',
                    'recipient_phone' => $recipient->recipient_phone ?? 'N/A',
                    'recipient_email' => $recipient->recipient_email ?? 'N/A',
                    'view_date' => $recipient->view_date ?? 'N/A',
                    'mail_code' => $mail->mail_code ?? 'N/A',
                    'mail_subject' => $mail->subject ?? 'N/A',
                    'attachments' => $mail->attachments->map(function ($attachment) {
                        return [
                            'file_name' => $attachment->file_name,
                            'file_path' => $attachment->file_path
                        ];
                    })->toArray()
                ];
            }
        }

        return response()->json(['status' => 'success', 'data' => $formattedData]);
    }
}
