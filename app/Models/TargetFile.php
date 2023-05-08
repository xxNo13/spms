<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating_id',
        'file_new_name',
        'file_default_name',
    ];

    public function rating() {
        return $this->belongsTo(Rating::class);
    }
}
