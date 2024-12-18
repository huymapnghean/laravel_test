<?php

namespace App\Http\Controllers;

use App\Service\TicketLabelService;
use Illuminate\Http\Request;

class TicketLabelController extends Controller {
    public function __construct(TicketLabelService $ticketLabelService)
    {
        $this->service = $ticketLabelService;
    }

    public function getAll() {
        return response()->json($this->service->getAllLabels(), 200);
    }
}
