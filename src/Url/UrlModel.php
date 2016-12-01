<?php

namespace Xparser\Url;

use Illuminate\Database\Query\Builder;
use Xparser\Site\Site;

/**
 * Xparser\UrlModel
 * 
 * @method static Builder| UrlModel expectProcessing($site)
 *
 * @property string url
 * @property bool processed
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
     * @return Url
     */
    public static function chooseNext(Site $site)
    {
        $next = self::expectProcessing($site)->first();

        /** @var UrlModel $urlModel */
        $urlModel = $next ?: self::firstOrCreate([
            'site_key' => $site->getKey(),
            'url' => $site->getUrl(),
        ]);

        $urlModel->processed = true;
        $urlModel->save();
            
        return new Url($urlModel->url, $site->getUrl());
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
            ->where('processed', false)
            ->oldest('updated_at');
    }
    
}