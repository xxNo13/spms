<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'review_id',
        'review_status',
        'review_date',
        'review_message',
        'approve_id',
        'approve_status',
        'approve_date',
        'approve_message',
        'type',
        'user_type',
        'added_id',
        'duration_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
