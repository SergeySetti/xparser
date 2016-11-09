<?php


namespace SergeySetti\Xparser\Parsers;

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

    public function __construct(Xparser $client, $html, UrlModel $urlModel)
    {
        $this->client   = $client;
        $this->html     = $html;
        $this->urlModel = $urlModel;
    }


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
                    ->merge(array_unique(array_get($found, 0)));
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
            $exists = $this->urlModel
                ->where('site_key', $this->client->getClientKey())
                ->where('url', $item)->count();
            if (! $exists) {
                $model = $this->urlModel->create([
                    'site_key' => $this->client->getClientKey(),
                    'url'      => $item,
                ]);
                $createdModels->push($model);
            }
        });

        return $createdModels;
    }

}