<?php

namespace Xparser\Url;

use Illuminate\Database\Query\Builder;
use Xparser\Site\Site;

/**
 * Xparser\UrlModel
 * 
 * @method static Builder| UrlModel expectProcessing($site)
 * 
 */
class UrlModel extends \Eloquent 
{
    protected $table = 'xparser_urls';

    protected $fillable = [
        'site_key',
        'processed',
        'url',
        'updated_at',
    ];

    /**
     * Returns next URL which expects to be processed
     * for the latest updated site.
     *
     * @param Site $site
     *
     * @return static
     */
    public static function chooseNext(Site $site)
    {
        $next = self::expectProcessing($site)->first();

        return $next ?: self::create([
            'site_key' => $site->getKey(),
            'url' => $site->getUrl(),
        ]);
    }

    /**
     * Setup the query for latest updated urls which was
     * not processed yet.
     *
     * @param $query
     * @param Site $site
     */
    public function scopeExpectProcessing($query, Site $site)
    {
        $query
            ->where('site_key', $site->getKey())
            ->whereNull('processed')
            ->orWhere('processed', false)
            ->oldest('updated_at');
    }
    
}