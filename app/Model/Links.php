<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Links
 * @package App\Model
 *
 * @property-read \App\Model\Sites $site
 * @property-read \App\Model\Childs $childs
 * @method static \Illuminate\Database\Eloquent\Builder|Links notProcessed($site_id)
 */
class Links extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url',
        'site_id',
        'qlt_links',
        'processed',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $site_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotProcessed($query, $site_id)
    {
        return $query->where('site_id', $site_id)->where('processed', false);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('App\Model\Sites');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childs() {
        return $this->belongsToMany('App\Model\Childs', 'child_link', 'link_id', 'child_id')->withTimestamps();
    }
}
