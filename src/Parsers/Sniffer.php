<?php


namespace Xparser\Parsers;

use Illuminate\Support\Collection;
use Xparser\Parsers\HttpClient;
use Xparser\Url\UrlModel;
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
    protected $site;
    /**
     * @var Xparser
     */
    protected $client;
    /**
     * @var string
     */
    protected $html;
    /**
     * @var UrlModel
     */
    protected $urlModel;
    
    public function proceed()
    {
        $this->saveNeeded($this->getNeeded());
    }

    /**
     *
     * @return Collection
     * @internal param Url $url
     *
     * @internal param Page $page
     */
    protected function getNeeded()
    {
        $needed   = collect();
        $patterns = $this->client->getUsefulUrlsPatterns();
        $patterns->each(function ($pattern) use (&$needed) {
            preg_match_all('/' . $pattern . '/im', $this->html, $found);
            if (! empty($found)) {
                $needed = $needed
                    ->merge(array_unique(array_get($found, 0)))->unique();
            }
        });

        return $needed;
    }

    /**
     * @param Collection $urls
     *
     * @return bool
     */
    protected function saveNeeded(Collection $urls)
    {
        $createdModels = collect();
        
        $urls->each(function ($item) use ($createdModels) {
            $item = html_entity_decode($item);
            $exists = $this->getUrlModel()
                ->where('site_key', $this->client->getClientKey())
                ->where('url', $item)->count();
            if (! $exists) {
                $model = $this->getUrlModel()->create([
                    'site_key' => $this->client->getClientKey(),
                    'url'      => $item,
                ]);
                $createdModels->push($model);
            }
        });

        return $createdModels;
    }

    /**
     * @return UrlModel
     */
    public function getUrlModel(): UrlModel
    {
        if (! empty($this->urlModel)) {
            return $this->urlModel;
        }

        return new UrlModel();
    }

    /**
     * @param UrlModel $urlModel
     */
    public function setUrlModel(UrlModel $urlModel)
    {
        $this->urlModel = $urlModel;
    }

    /**
     * @param string $html
     */
    public function setHtml(string $html)
    {
        $this->html = $html;
    }

    /**
     * @param Xparser $client
     */
    public function setClient(Xparser $client)
    {
        $this->client = $client;
    }

}