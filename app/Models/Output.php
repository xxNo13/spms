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
        'sub_funct_id',
        'funct_id',
        'duration_id',
        'added_by'
    ];

    public function sub_funct(){
        return $this->belongsTo(SubFunct::class);
    }

    public function funct(){
        return $this->belongsTo(Funct::class);
    }
 
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
