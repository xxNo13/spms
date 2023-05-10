<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_link',
        'footer_link',
        'form_link',
    ];
}
