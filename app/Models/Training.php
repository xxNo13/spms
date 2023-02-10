<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_name',
        'links' => 'array',
        'user_id',
        'keywords'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
