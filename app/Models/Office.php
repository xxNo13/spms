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

    public function institutes() {
        return $this->hasMany(Institute::class);
    }

    public function parent() {
        return $this->belongsTo(Office::class, 'parent_id', 'id');
    }

    public function child() {
        return $this->hasMany(Office::class, 'parent_id', 'id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'office_user')->withPivot('isHead');
    }


    public function getDepthAttribute()
    {
        return $this->GetParentHelper($this->id);
    }
    
    // Recursive Helper function
    function GetParentHelper($id, $depth = 0) {
        $model = Office::find($id);
    
        if ($model->parent_id != null) {
            $depth++;
    
            return $this->GetParentHelper($model->parent_id, $depth);
        } else {
            return $depth;
        }
    }
}
