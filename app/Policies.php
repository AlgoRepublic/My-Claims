<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policies extends Model
{
    protected $fillable = ['name', 'type', 'document', 'document_original_name', 'added_by', 'added_by_type', 'institute_name', 'policy_number'];

    public function User()
    {
        return $this->belongsTo('App\User', 'added_by');
    }
}
