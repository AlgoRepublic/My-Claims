<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['user_name', 'email', 'contact_number', 'message', 'send_to'];
}
