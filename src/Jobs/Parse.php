<?php

namespace Xparser\Jobs;

use Xparser\Parsers\Configs\ConfigInterface;
use Xparser\Parsers\ParserBuilder;
use Xparser\Parsers\Sniffer;
use Xparser\Types\AbstractType;
use Xparser\Url\Url;
use Xparser\Url\UrlPipeline;
use Xparser\Xparser;


class Parse extends Job
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
     * @var ConfigInterface
     */
    public $siteConfig;
    /**
     * @var Xparser
     */
    protected $client;

    /**
     * Parse constructor.
     *
     * @param Xparser $client
     */
    public function __construct(Xparser $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $builder = new ParserBuilder($this->client);
        $parser = $builder->build();
        $this->client->setParser($parser);
        
        return $this;
    }

    /**
     * @param $url
     */
    public function prepare($url)
    {
        $this->url = $url;
        $this->url->touch();
        $this->site = $this->url->site;
        $this->siteConfig = $this->makeSiteConfig($this->site);
    }
    
    public function parseUrlsFromPage()
    {
        $urlSniffer = new Sniffer($this->site);
        $urlSniffer->proceed($this->url);
    }

    public function parseDataFromPages()
    {
        foreach ($this->getSchema() as $entityClass => $fields) {
            $this->makeTypeEntity($entityClass)->extract($fields);
        }
    }

}
