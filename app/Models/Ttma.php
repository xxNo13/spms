<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ttma extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'output',
        'remarks',
        'head_id',
        'deadline',
        'duration_id',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'ttma_user');
    }

    public function head() {
        return $this->belongsTo(User::class, 'head_id');
    }
     
    public function messages() {
        return $this->hasMany(Message::class);
    }
}
