<?php


namespace SergeySetti\Xparser\Parsers;

use Illuminate\Support\Collection;
use Xparser\Site;
use Xparser\Url;

class Sniffer
{
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @param Url $url
     */
    public function proceed(Url $url)
    {
        $this->saveNeeded($this->getNeeded($url));
    }
    
    /**
     * @param Url $url
     * @return Collection
     */
    public function getNeeded(Url $url)
    {
        $html = Page::getByUrl($url->url);
        
        $needed = new Collection();
        $patterns = $this->getPatterns()->all();
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
    public function saveNeeded(Collection $urls)
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
        return new Patterns($this->site);
    }
}