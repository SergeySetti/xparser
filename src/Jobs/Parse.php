<?php

namespace SergeySetti\Xparser\Jobs;

use Xparser\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Xparser\Jobs\Parse\ChoseUrl;
use Xparser\Parsers\Configs\NewsTwoRu;
use Xparser\Parsers\Sniffer;
use Xparser\Site;
use Xparser\Types\AbstractType;
use Xparser\Url;

class Parse extends Job implements SelfHandling
{

    /**
     * @var Site $site
     */
    public $site;
    /**
     * @var Url $url
     */
    public $url;
    
    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $this->url = app()->make(ChoseUrl::class)->chose();
        $this->url->touch();
        
        $this->site = $this->url->site;

        $urlSniffer = new Sniffer($this->site);
        $urlSniffer->proceed($this->url);
        
        $siteConfig = app()->make('Xparser\Parsers\Configs\\'.$this->site->config_name);
        $pageEntities = array_keys($siteConfig->fields());

        foreach ($pageEntities as $entityClass) {
            /** @var AbstractType $entity */
            $entity = app()->make($entityClass, [$siteConfig, $this->url->url]);
            $entity->extract($entityClass);
            
        }
        
        $this->url->processed = true;
        $this->url->save();
        
        return $this;
    }
}
