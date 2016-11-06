<?php


namespace SergeySetti\Xparser\Parsers;

use Illuminate\Support\Collection;
use Xparser\Parsers\Page;
use Xparser\Url\Url;
use Xparser\Xparser;

/**
 * This class extracts URLs from HTML from the given
 * URL using URL-patterns which belongs to the given
 * site.
 *
 * After extraction URLs will be stored in the DB
 * and will be processed during some of the next runs
 * of the Parse command.
 *
 * Class Sniffer
 * @package Xparser\Parsers
 */
class Sniffer
{
    public function __construct(Xparser $client)
    {
        $this->client = $client;
    }

    /**
     * @param Page $page
     *
     */
    public function proceed(Page $page)
    {
        $this->saveNeeded($this->getNeeded($page));
    }

    /**
     *
     * @param Url $url
     *
     * @return Collection
     * @internal param Page $page
     */
    private function getNeeded(Url $url)
    {
        $html = Page::getHtmlByUrl($url);
        
        $needed = new Collection();
        $patterns = $this->getPatterns();
        $patterns->each(function($pattern) use (&$needed, $html){
            preg_match_all($pattern, $html, $found);
            if( ! empty($found)) {
                $needed = $needed->merge(array_unique(array_get($found, 0)));
            }
        });
        
        return $needed;
    }

    /**
     * @param Collection $urls
     * @return bool
     */
    private function saveNeeded(Collection $urls)
    {
        $urls->each(function($item){
            $exists = Url::where('site_id', $this->site->id)
                ->where('url', $item)->count();
            if( ! $exists) {
                Url::create([
                    'site_id' => $this->site->id,
                    'url' => $item,
                ]);
            }
        });
        
        return ;
    }

    public function getPatterns()
    {
        return $this->site->patterns;
    }
}