<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model {
    protected $table = 'user_detail';

    const ADMIN = 1;
    const AGENT = 2;
    const USER = 3;

    protected $fillable = ['id', 'name', 'age', 'role'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id', 'user_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'id');
    }
}
