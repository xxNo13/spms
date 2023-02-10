<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'efficiency',
        'quality',
        'timeliness',
    ];
}
