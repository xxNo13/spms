<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'target',
        'required',
        'output_id',
        'suboutput_id',
        'duration_id'
    ];

    public function output() {
        return $this->belongsTo(Output::class);
    }

    public function suboutput() {
        return $this->belongsTo(Suboutput::class);
    }

    public function standards() {
        return $this->hasMany(Standard::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'target_user')
            ->withPivot('target_output')
            ->withPivot('alloted_budget')
            ->withPivot('responsible');
    }
}
