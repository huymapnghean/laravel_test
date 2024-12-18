<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableTicketLabel extends Model {
    protected $table = 'table_ticket_label';

    protected $fillable = ['ticket_id', 'label_id'];

    public function ticket() {
        return $this->beLongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function label() {
        return $this->beLongsTo(TicketLabel::class, 'label_id', 'id');
    }
}
