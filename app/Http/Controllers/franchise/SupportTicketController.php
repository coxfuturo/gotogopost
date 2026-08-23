<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CMS;
use App\Models\Franchise;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        $datas = SupportTicket::join('support_ticket_messages', 'support_tickets.id', '=', 'support_ticket_messages.support_ticket_id')
            ->where('support_ticket_messages.messageable_type', 'App\Models\Franchise')
            ->where('support_ticket_messages.messageable_id', Auth::guard('franchise')->user()->id)
            ->select('support_tickets.id', 'support_tickets.status', 'support_tickets.title', 'support_ticket_messages.body', 'support_ticket_messages.is_read')
            ->groupBy('support_tickets.id')
            ->get();

        // dd($datas);
        return view('franchise.support-ticket.index', compact('datas'));
    }

    public function getDetails($id): Renderable|RedirectResponse
    {
        SupportTicketMessage::where('support_ticket_id', $id)->whereIn('messageable_type', [Admin::class, CMS::class])->update(['is_read' => 1]);
        $supportTicketsMessages = SupportTicketMessage::with('supportTicket')->where('support_ticket_id', $id)->get();
        $data = SupportTicketMessage::with('supportTicket')->where('support_ticket_id', $id)->where('messageable_type', 'App\Models\Admin')->first();
        $senderDetail = [];
        if ($data) {
            $senderDetail = Admin::where('id', $data->messageable_id)->first();
        }
        $supportTicket = SupportTicket::find($id);
        return view('franchise.support-ticket.chat', ['supportTicket' => $supportTicket, 'supportTicketsMessages' => $supportTicketsMessages, 'id' => $id, 'senderDetail' => $senderDetail]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): Renderable|RedirectResponse
    {
        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        $supportTicket = SupportTicket::create([
            'title' => $request->name,
            'auth_id' => Auth::guard('franchise')->user()->id
        ]);

        if ($request->description) {
            $this->validate($request, [
                'description' => 'required'
            ]);


            SupportTicketMessage::create([
                'support_ticket_id' => $supportTicket->id,
                'messageable_id' => Auth::guard('franchise')->user()->id,
                'messageable_type' => Franchise::class,
                'body' => $request->description,
            ]);

            return redirect()->back()->with(['success' => 'Ticket reply sent successfully']);
        }

        return redirect()->back()->with(['error' => 'Something went wrong. Please try again.']);
    }

    public function update(Request $request, $id): Renderable|RedirectResponse
    {

        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        $supportTicket = SupportTicket::find($id);

        if ($supportTicket) {
            $supportTicket->title = $request->name;
            $supportTicket->status = $request->status;
            $supportTicket->save();
        }

        if ($request->description) {
            $this->validate($request, [
                'description' => 'required'
            ]);

            $supportTicketMessage = SupportTicketMessage::where('support_ticket_id', $id)
                ->where('messageable_type', 'App\Models\Franchise')
                ->latest('created_at')
                ->first();

            if ($supportTicketMessage) {

                $supportTicketMessage->body = $request->description;
                $supportTicketMessage->is_read = $request->read_unread;
                $supportTicketMessage->save();
            }

            return redirect()->back()->with(['success' => 'Ticket updated successfully']);
        }

        return redirect()->back()->with(['error' => 'Something went wrong. Please try again.']);
    }

    public function delete(Request $request, $id): Renderable|RedirectResponse
    {
        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        $supportTicket = SupportTicket::find($id);
        $SupportTicketMessage = SupportTicketMessage::where('support_ticket_id', $id);

        if ($supportTicket && $SupportTicketMessage) {
            $SupportTicketMessage->delete();
            $supportTicket->delete();
            return redirect()->back()->with(['success' => 'Ticket deleted successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong. Please try again.']);
    }


    public function chatStore(Request $request)
    {

        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }
        if ($request->message) {
            // Create SupportTicketMessage record
            $message = SupportTicketMessage::create([
                'support_ticket_id' => $request->id,
                'messageable_id' => Auth::guard('franchise')->user()->id,
                'messageable_type' => Franchise::class,
                'body' => $request->message,
            ]);

            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'message reply sent successfully',
                'messageData' => $message
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'message could not sent successfully',
        ]);
    }


    public function updateChat(Request $request, $id): Renderable|RedirectResponse
    {

        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        if ($request->message) {
            $this->validate($request, [
                'message' => 'required'
            ]);

            $supportTicketMessage = SupportTicketMessage::where('id', $id)->first();
            if ($supportTicketMessage) {
                $supportTicketMessage->body = $request->message;
                // $supportTicketMessage->is_read = $request->read_unread;
                $supportTicketMessage->save();
            }

            return redirect()->back()->with(['success' => 'Message updated successfully']);
        }

        return redirect()->back()->with(['error' => 'Something went wrong. Please try again.']);
    }

    public function deleteChat(Request $request, $id): Renderable|RedirectResponse
    {
        if ($request->close_ticket) {
            SupportTicket::where('id', $request->id)->update(['status' => 2]);

            return redirect()->route('franchise.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        $SupportTicketMessage = SupportTicketMessage::where('id', $id);

        if ($SupportTicketMessage) {
            $SupportTicketMessage->delete();
            return redirect()->back()->with(['success' => 'Message deleted successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong. Please try again.']);
    }


    public function clearAll(): Renderable|RedirectResponse
    {
        SupportTicketMessage::query()->update(['is_read' => 1]);
        return redirect()->back()->with(['success' => 'Clear all notification successfully']);
    }
}
