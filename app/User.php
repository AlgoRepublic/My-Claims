<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'mobile', 'role_id', 'email', 'password', 'identity_document_number', 'package_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsTo('App\Roles', 'role_id');
    }

    public function policies()
    {
        return $this->hasMany('App\Policies', 'added_by', 'id');
    }

    public function beneficiaries()
    {
        return $this->hasMany('App\Beneficiaries', 'added_by', 'id');
    }

    public function payment()
    {
        return $this->hasOne('App\UserPayment','user_id', 'id');
    }
}
