<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funct extends Model
{
    use HasFactory;

    protected $fillable = [
        'funct'
    ];

    public function sub_functs() {
        return $this->hasMany(SubFunct::class);
    }

    public function outputs() {
        return $this->hasMany(Output::class);
    }
}
