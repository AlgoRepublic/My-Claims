<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficiaries extends Model
{
    protected $fillable = ['name', 'surname', 'identity_document_number', 'cell_number', 'added_by'];
}
