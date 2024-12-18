<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableTicketCategory extends Model {
    protected $table = 'table_ticket_categories';

    protected $fillable = ['ticket_id', 'category_id'];

    public function ticket() {
        return $this->beLongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function category() {
        return $this->beLongsTo(TicketCategories::class, 'category_id', 'id');
    }
}
