<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'eff_5',
        'eff_4',
        'eff_3',
        'eff_2',
        'eff_1',
        'qua_5',
        'qua_4',
        'qua_3',
        'qua_2',
        'qua_1',
        'time_5',
        'time_4',
        'time_3',
        'time_2',
        'time_1',
        'target_id',
        'user_id',
        'duration_id',
    ];

    public function target(){
        return $this->belongsTo(Target::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
