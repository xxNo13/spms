<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        'office_abbr',
        'building',
        'parent_id'
    ];

    public function parent() {
        return $this->belongsTo(Office::class, 'parent_id');
    }

    public function child() {
        return $this->hasMany(Office::class, 'parent_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'office_user')->withPivot('isHead');
    }
}
