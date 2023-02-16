<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmt extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'user_id',
        'isHead'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
} 
