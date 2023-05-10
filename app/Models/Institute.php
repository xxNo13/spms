<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_name',
        'office_id'
    ];

    public function office() {
        return $this->belongsTo(Office::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'institute_user')->withPivot('isProgramChair');
    }
}
