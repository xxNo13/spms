<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_eff',
        'old_qua',
        'old_time',
        'old_ave',
        'new_eff',
        'new_qua',
        'new_time',
        'new_ave',
        'user_id',
        'rating_id',
        'updated_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function rating() {
        return $this->belongsTo(Rating::class);
    }
}
