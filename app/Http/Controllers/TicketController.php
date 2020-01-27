<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ticket;

class TicketController extends Controller
{
    /**
     * Instantiate a new TicketController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the ticket.
     *
     * @return Response
     */
    public function get($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            return response()->json(['ticket' => $ticket], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'ticket not found!'], 404);
        }
    }

    /**
     * Get all the tickets for the user.
     *
     * @return Response
     */
    public function getByUser()
    {
        try {
            $result = Ticket::where('created_by', Auth::user()->id)
                ->where('parent_id', NULL)
                ->with('user', 'child')
                ->get();

            return response()->json(['result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error while retrieving tickets!'], 404);
        }
    }

    /**
     * Get all the tickets for admin.
     *
     * @return Response
     */
    public function all()
    {
        try {
            $result = Ticket::where('parent_id', NULL)
                ->with('user', 'child')
                ->get();

            return response()->json(['result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error while retrieving tickets!'], 404);
        }
    }

    /**
     * add new ticket.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
            'status' => 'string',
            'parent' => 'integer'
        ]);

        try {
            $ticket = new Ticket;
            $ticket->subject = $request->input('subject');
            $ticket->body = $request->input('body');
            $ticket->status = $request->input('status') ?? 'pending';
            $ticket->created_by = Auth::user()->id;
            $parent = $request->input('parent');
            if ($parent > 0) {
                $ticket->parent_id = $parent;
            }
            $ticket->save();

            return response()->json(['ticket' => $ticket, 'message' => 'Done'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Create failed!'], 409);
        }
    }

    /**
     * reply to ticket.
     *
     * @param  Request  $request
     * @return Response
     */
    public function reply(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
            'status' => 'required|string',
            'parent' => 'integer'
        ]);

        try {
            $ticket = new Ticket;
            $ticket->subject = $request->input('subject');
            $ticket->body = $request->input('body');
            $ticket->created_by = Auth::user()->id;
            $parent = $request->input('parent');
            if ($parent > 0) {
                $ticket->parent_id = $parent;
            }
            $ticket->save();

            $parentTicket = Ticket::findOrFail($parent);
            $parentTicket->status = $request->input('status') ?? 'pending';
            $parentTicket->save();

            return response()->json(['ticket' => $ticket, 'message' => 'Done'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Create failed!'], 409);   // @TODO
        }
    }

}
