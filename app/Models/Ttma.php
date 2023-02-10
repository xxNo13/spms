<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ttma extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'user_id',
        'output',
        'remarks',
        'message',
        'comments',
        'head_id',
        'deadline',
        'duration_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
