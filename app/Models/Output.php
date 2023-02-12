<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'output',
        'type',
        'user_type',
        'sub_funct',
        'funct_id',
        'duration_id'
    ];

    public function suboutputs() {
        return $this->hasMany(Suboutput::class);
    }

    public function targets() {
        return $this->hasMany(Target::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'output_user');
    }
}
