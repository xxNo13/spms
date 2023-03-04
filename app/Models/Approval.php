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

    public function reviewers() {
        return $this->belongsToMany(User::class, 'approval_review')
        ->withPivot('review_status')
        ->withPivot('review_date')
        ->withPivot('review_message');
    }
}
