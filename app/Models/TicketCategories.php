<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategories extends Model {
    protected $table = 'ticket_categories';

    protected $fillable = ['id', 'name'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }
}
