<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Blogs extends Model
{
    protected $fillable = ['image', 'title', 'content', 'added_by'];

    public function getImageAttribute($value)
    {
        return URL::to('/').Storage::url($value);
    }
}
