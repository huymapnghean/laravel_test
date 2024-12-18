<?php

namespace App\Service;

use App\Models\TicketLabel;

class TicketLabelService {
    public function getAllLabels() {
        return TicketLabel::query()->get();
    }
}
