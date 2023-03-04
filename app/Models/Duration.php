<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration_name',
        'start_date',
        'end_date',
        'type'
    ];
}
