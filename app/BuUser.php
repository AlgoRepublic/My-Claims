<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BuUser extends Authenticatable
{
    use Notifiable;

    protected $guard = 'business';

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'bu_role_id',
        'bu_company_id',
        'reset_password_token',
        'reset_password_token_date',
        'remember_token',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\BuRole', 'bu_role_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'bu_company_id');
    }
}
