<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Label;

class Ticket extends Model {
    protected $table = 'tickets'; // ten cua bang table

        protected $fillable = ['id', 'user_id', 'title', 'message', 'label_id', 'category_id', 'priority']; // fillable su dung nham gan hang loat cho cac cot duoc chi dinh

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function labels() {
        return $this->belongsTo(Label::class, 'id', 'label_id');
    }
}
