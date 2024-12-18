<?php

namespace App\Http\Controllers;

use App\Service\TicketCategoryService;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller {
    public function __construct(TicketCategoryService $ticketCategoryService) {
        $this->service = $ticketCategoryService;
    }

    public function getAll() {
        return response()->json($this->service->getAll(), 200);
    }
}
