<?php


namespace Xparser\Parsers;


use SergeySetti\Xparser\Parsers\Sniffer;
use Xparser\Site\Site;
use Xparser\Url\UrlPipeline;
use Xparser\Xparser;

class ParserBuilder
{
    /**
     * @var Xparser
     */
    protected $client;
    /**
     * @var UrlPipeline
     */
    protected $urlsPipeline;
    protected $site;
    protected $types;
    protected $sniffer;

    /**
     * ParserBuilder constructor.
     *
     * @param Xparser $client
     */
    public function __construct(Xparser $client)
    {
        $this->client = $client;
        $this->parser = new Parser();
        $this->parser->setClient($this->client);
    }
    
    public function build()
    {
        if (empty($this->parser->getClient())) {
            throw new \Exception('Xparser client not set');
        }

        $this->setSite();
        $this->setUrlsPipeline();
        $this->setSniffer();
        $this->client->setParser($this->parser);

        return $this->parser;
    }

    protected function setSite()
    {
        $site = new Site($this->parser->getClient());
        $this->parser->setSite($site);

        return $this;
    }
    
    protected function setUrlsPipeline()
    {
        $urlsPipeline = app()->make(
            UrlPipeline::class, 
            [$this->parser->getClient()]
        );
        $this->parser->setUrlsPipeline($urlsPipeline);
    }

    protected function setSniffer()
    {
        $this->sniffer = new Sniffer($this->client);
    }


}