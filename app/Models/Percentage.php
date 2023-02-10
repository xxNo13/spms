<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    use HasFactory;

    protected $fillable = [
        'core',
        'strategic',
        'support',
        'type',
        'user_type',
        'user_id',
        'duration_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
