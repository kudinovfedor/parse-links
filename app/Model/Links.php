<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Links
 *
 * @property int $id
 * @property string $url
 * @property string $path
 * @property int $qlt_links
 * @property int $processed
 * @property int $site_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Childs[] $childs
 * @property-read \App\Model\Sites $site
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links notProcessed($site_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereQltLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Links whereUrl($value)
 * @mixin \Eloquent
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
    public function childs()
    {
        return $this
            ->belongsToMany(
                'App\Model\Childs',
                'child_link',
                'link_id',
                'child_id'
            )
            //->withTimestamps()
            ;
    }
}
