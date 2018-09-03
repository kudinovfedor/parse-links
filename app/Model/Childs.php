<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Childs
 * @package App\Model
 *
 * @property-read \App\Model\Links $links
 */
class Childs extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function links()
    {
        return $this->belongsToMany('App\Model\Links', 'child_link', 'child_id', 'link_id')->withTimestamps();
    }
}
