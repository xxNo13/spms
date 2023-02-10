<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type'
    ];

    
    public function users(){
        return $this->belongsToMany(User::class, 'account_type_user');
    }
}
