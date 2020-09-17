<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policies extends Model
{
    protected $fillable = ['name', 'type', 'document', 'document_original_name', 'added_by'];
}
