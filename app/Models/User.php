<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    public function score_review() {
        return $this->hasOne(ScoreReview::class);
    }
    
    public function committee() {
        return $this->hasOne(Committee::class);
    }

    public function pmt() {
        return $this->hasOne(Pmt::class);
    }
    
    public function trainings() {
        return $this->hasMany(Training::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function standards() {
        return $this->hasMany(Standard::class);
    }

    public function percentages() {
        return $this->hasMany(Percentage::class);
    }

    public function sub_percentages() {
        return $this->hasMany(SubPercentage::class);
    }

    public function approvals() {
        return $this->hasMany(Approval::class);
    }

    public function user_approvals() {
        return $this->belongsToMany(Approval::class, 'approval_review')
        ->withPivot('review_status')
        ->withPivot('review_date')
        ->withPivot('review_message');
    }

    public function ttmas() {
        return $this->belongsToMany(Ttma::class, 'ttma_user');
    }

    public function offices(){
        return $this->belongsToMany(Office::class, 'office_user')->withPivot('isHead');
    }

    public function account_types(){
        return $this->belongsToMany(AccountType::class, 'account_type_user');
    }

    public function sub_functs(){
        return $this->belongsToMany(SubFunct::class, 'sub_funct_user');
    }

    public function outputs(){
        return $this->belongsToMany(Output::class, 'output_user');
    }

    public function suboutputs(){
        return $this->belongsToMany(Suboutput::class, 'suboutput_user');
    }

    public function targets(){
        return $this->belongsToMany(Target::class, 'target_user')
        ->withPivot('target_output')
        ->withPivot('alloted_budget')
        ->withPivot('responsible')
        ->withPivot('target_allocated');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
