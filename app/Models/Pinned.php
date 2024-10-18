<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinned extends Model
{
    protected $fillable = [
        'user_id',
        'pinned_id',
        'pinned_type',
    ];
}
