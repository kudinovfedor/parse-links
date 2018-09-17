<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Sites
 *
 * @property int $id
 * @property string $url
 * @property string $domain
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Links[] $links
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites linksCount()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites notProcessed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites processed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Sites whereUserId($value)
 * @mixin \Eloquent
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopeProcessed()
    {
        return $this->links()->where('processed', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopeNotProcessed()
    {
        return $this->links()->where('processed', false);
    }

    /**
     * @return int
     */
    public function scopeLinksCount()
    {
        return $this->links()->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany('App\Model\Links', 'site_id')->orderBy('path');
    }
}
