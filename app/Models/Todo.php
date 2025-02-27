<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $hidden = [
        "user_id",
        "created_at",
    ];
}
