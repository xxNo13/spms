<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPercentage extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'type',
        'user_type',
        'sub_funct_id',
        'user_id',
        'duration_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
