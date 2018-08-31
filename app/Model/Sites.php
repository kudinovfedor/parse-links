<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sites
 * @package App\Model
 *
 * @property-read \App\Model\Links $links
 */
class Sites extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url',
        'domain',
    ];


    public function scopeProcessed()
    {
        return $this->links()->where('processed', true);
    }

    public function scopeNotProcessed()
    {
        return $this->links()->where('processed', false);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany('App\Model\Links', 'site_id')->orderBy('path');
    }
}
