<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpcrReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'status',
        'type',
        'duration_id',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ipcr_reviewer() {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
