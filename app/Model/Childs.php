<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Childs
 *
 * @property int $id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Links[] $links
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Childs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Childs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Childs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Childs whereUrl($value)
 * @mixin \Eloquent
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
     * @var array
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function links()
    {
        return $this->belongsToMany('App\Model\Links', 'child_link', 'child_id', 'link_id')->withTimestamps();
    }
}
