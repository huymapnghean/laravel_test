<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLabel extends Model {
    protected $table = 'ticket_label';

    protected $fillable = ['id', 'name'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'label_id', 'id');
    }
}
