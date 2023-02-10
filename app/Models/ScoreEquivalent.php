<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreEquivalent extends Model
{
    use HasFactory;

    protected $fillable = [
        'out_from',
        'out_to',
        'verysat_from',
        'verysat_to',
        'sat_from',
        'sat_to',
        'unsat_from',
        'unsat_to',
        'poor_from',
        'poor_to',
    ];
}
