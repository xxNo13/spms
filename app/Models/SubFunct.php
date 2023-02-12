<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFunct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_funct',
        'type',
        'user_type',
        'duration_id',
        'funct_id'
    ];

    public function funct() {
        return $this->belongsTo(Funct::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'sub_funct_user');
    }
}
