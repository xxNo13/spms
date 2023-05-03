<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designated_id',
        'designated_status',
        'prog_chair_id',
        'prog_chair_status',
        'hr_status',
        'eval_committee_status',
        'review_committee_status',
        'duration_id',
        'type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
