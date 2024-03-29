<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'committee_type',
        'user_id'
    ];

    public function user (){
        return $this->belongsTo(User::class);
    }
}
