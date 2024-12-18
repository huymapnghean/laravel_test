<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $table = 'ticket'; // ten cua bang table

    protected $fillable = ['id', 'user_id', 'title', 'message', 'label_id', 'category_id', 'priority', 'created_at']; // fillable su dung nham gan hang loat cho cac cot duoc chi dinh

    public function user_detail() {
        return $this->belongsTo(UserDetail::class, 'user_id', 'id');
    }

    public function ticket_label() {
        return $this->hasMany(TableTicketLabel::class, 'ticket_id', 'id');
    }

    public function ticket_category() {
        return $this->hasMany(TableTicketCategory::class, 'ticket_id', 'id');
    }
}
