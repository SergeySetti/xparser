<?php


namespace SergeySetti\Xparser\Jobs\Parse;


use Xparser\Site;
use Xparser\Url;

class ChoseUrl
{
    /**
     * @return Url
     */
    public function chose()
    {
        /** @var Site $site */
        $site = Site::orderBy('updated_at', 'desc')
            ->first();

        /** @var Url $url */
        $url = Url::where('site_id', $site->id)
            ->whereNull('processed')
            ->orWhere('processed', false)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $this->ifEmpty($site, $url);
    }

    /**
     * @param Site $site
     * @param Url $url
     * @return Url|static
     */
    public function ifEmpty($site, $url)
    {
        if( ! $url) {
            $url = Url::create([
                'site_id' => $site->id,
                'url' => $site->url,
            ]);
        }
        
        return $url;
    }
}
