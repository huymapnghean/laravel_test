<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TickerLabel extends Model {
    protected $table = 'ticker_label';

    protected $fillable = ['id', 'name'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'label_id', 'id');
    }
}
