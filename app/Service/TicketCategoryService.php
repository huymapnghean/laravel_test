<?php

namespace App\Service;

use App\Models\TicketCategories;

class TicketCategoryService {
    public function getAll() {
        return TicketCategories::query()->get();
    }
}
