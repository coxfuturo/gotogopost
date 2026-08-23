<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\Admin;

use App\Models\User;
use App\Models\UserPayment;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;




class userController extends Controller

{

    public function index(Request $request): Renderable|JsonResponse|RedirectResponse

    {

        $roleUsers = User::orderBy('created_at', 'desc')->get();

        // dd($roleUsers);
        $roles = Role::where('name', '!=', 'admin')->pluck('name', 'name')->all();

        return view('admin.user.index', compact('roleUsers', 'roles'));
    }





    public function role_user()

    {

        $roleUsers = Admin::orderBy('created_at', 'desc')->get();

        $roles = Role::where('name', '!=', 'admin')->pluck('name', 'name')->all();

        return view('admin.roles.users.index', compact('roleUsers', 'roles'));
    }



    public function store(Request $request)

    {



        $this->validate($request, [

            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'pincode' => 'required',
            'address' => 'required',
            'password' => 'required|min:3|confirmed',
            'phone' => 'required|numeric',

        ]);



        try {

            $image = NULL;



            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $extension = $file->getClientOriginalName();

                $img = time() . '_' . $extension;

                $file->move('tenancy/assets/admin/User/', $img);

                $image = $img;
            }

            User::create([

                'name' => $request->name,

                'email' => $request->email,
                'address' => $request->address,
                'pincode' => $request->pincode,

                'password' => Hash::make($request->password),

                'phone' => $request->phone,

                'image' => $image,

            ]);

            return back()->with('success', 'New User Added');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function update(Request $request, $id)

    {

        $this->validate($request, [

            'name' => 'required|string|max:191',

            'email' => 'required|email|max:191',
            'pincode' => 'required',
            'address' => 'required',
            'email' => 'required|email|max:191',

            'phone' => 'required|numeric',

        ]);



        try {

            $user = User::findOrfail($id);

            $user->name = $request->name;

            $user->email = $request->email;
            $user->pincode = $request->pincode;
            $user->address = $request->address;

            $user->phone = $request->phone;

            $user->save();

            return back()->with('success', 'User Updated Successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function delete($id)

    {

        try {

            User::findorfail($id)->delete();

            return back()->with('success', 'User Deleted Successfully');
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function status_update(Request $request)

    {

        try {

            $user = User::findorfail($request->id);

            $status = $request->status;

            $user->status = $status;

            $user->save();

            return response()->json(['success' => true, 'status' => $user->status]);
        } catch (\Throwable $th) {

            return response()->json(['success' => false]);
        }
    }


    public function paymentHistory(Request $request)
    {

        try {

            $paymentHistory = UserPayment::orderBy('id', 'DESC');

            // Filter by start date if provided in the request
            if ($request->has('start_date') && !empty($request->start_date)) {
                $startDate = Carbon::parse($request->start_date)->startOfDay(); // Convert to start of the day
                $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
            }

            // Filter by end date if provided in the request
            if ($request->has('end_date') && !empty($request->end_date)) {
                $endDate = Carbon::parse($request->end_date)->endOfDay(); // Convert to end of the day
                $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
            }

            // Execute the query and get the results
            $paymentHistory = $paymentHistory->get();

            // Return the view with the payment history
            return view('admin.payment.userHistory', compact('paymentHistory'));
        } catch (Exception $e) {
            // Handle any general exceptions
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment: ' . $e->getMessage(),
            ]);
        }
    }

    public function printPaymentHistory(Request $request)

    {

        $data = FranchisePayment::where('id', $request->id)->first();
        $franchiseDetails = Franchise::findOrFail($data->franchise_id);
        $otherPageContent = View('admin.payment.printCommissionDetail', ['data' => $data, 'franchiseDetails' => $franchiseDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
