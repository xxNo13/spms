<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'superior1_id',
        'superior1_status',
        'superior1_date',
        'superior1_message',
        'superior2_id',
        'superior2_status',
        'superior2_date',
        'superior2_message',
        'type',
        'user_type',
        'added_id',
        'duration_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
