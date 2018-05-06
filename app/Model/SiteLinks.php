<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteLinks extends Model
{
    protected $fillable = [
        'url',
        'site_id',
    ];
}
