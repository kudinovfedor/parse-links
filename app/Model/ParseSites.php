<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ParseSites
 * @package App\Model
 */
class ParseSites extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany('App\Model\SiteLinks', 'site_id');
    }
}
