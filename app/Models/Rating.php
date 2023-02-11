<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'accomplishment',
        'efficiency',
        'quality',
        'timeliness',
        'average',
        'remarks',
        'target_id',
        'user_id',
        'duration_id'
    ];

    public function target(){
        return $this->belongsTo(Target::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
