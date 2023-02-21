<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suboutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'suboutput',
        'output_id',
        'duration_id',
        'added_by'
    ];

    public function output() {
        return $this->belongsTo(Output::class);
    }

    public function targets() {
        return $this->hasMany(Target::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'suboutput_user');
    }
}
