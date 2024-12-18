<?php

namespace App\Http\Controllers;

use App\Service\ManageTicketService;
use Illuminate\Http\Request;

class ManageTicketController extends Controller {
    public function __construct(ManageTicketService  $manageTicketService) {
        $this->service = $manageTicketService;
    }

    public function updateManageTicket(Request $request) {
        return $this->service->updateManageTicket($request);
    }
}
