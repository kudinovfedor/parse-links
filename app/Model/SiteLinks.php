<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SiteLinks
 * @package App\Model
 */
class SiteLinks extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url',
        'site_id',
        'status',
        'external',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('App\Model\ParseSites');
    }
}
