<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'status'];

    public function users()
    {
        return $this->hasMany('App\BuUser', 'bu_company_id', 'id');
    }

}
