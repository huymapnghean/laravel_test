<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SendEmailNotification;
use App\Service\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller {
    public function __construct(TicketService $ticketService) {
        $this->service = $ticketService;
    }

    public function createTicket(UpdateTicketRequest $request) {
        return $this->service->createTicket($request);
    }

    public function listTicket(Request $request) {
        return response()->json($this->service->listTicket($request));
    }

    public function getTicketByID($id) {
        $ticket = $this->service->getTicketByID($id);

        if ($ticket) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket found successfully.',
                'data' => $ticket,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ticket not found.',
            'data' => null,
        ], 404);
    }

    public function updateTicket(UpdateTicketRequest $request, $id) {
        return $this->service->updateTicket($id, $request);
    }

    public function deleteTicket(Request $request, $id) {
        return response()->json($this->service->deleteTicket($id, $request), 200);
    }

    public function updateAgentTicket(Request $request)
    {
        return $this->service->updateAgentTicket($request);
    }
}
