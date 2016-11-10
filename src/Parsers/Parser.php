<?php


namespace Xparser\Parsers;

use Xparser\AbstractType;
use Xparser\HttpClient\HttpClient;
use Xparser\Jobs\Job;
use Xparser\Site\Site;
use Xparser\Types\TypeInstancesCollection;
use Xparser\Url\UrlPipeline;
use Xparser\Xparser;

class Parser extends Job
{
    /**
     * @var HttpClient
     */
    private $httpClient;
    /**
     * @var Xparser
     */
    private $client;
    /**
     * @var Site
     */
    private $site;
    /**
     * @var UrlPipeline
     */
    private $urlsPipeline;
    /**
     * @var TypeInstancesCollection
     */
    private $types;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function handle()
    {
        $urlToProceed = $this->getUrlToProcess();

        $html = $this->httpClient->getHtmlByUrl($urlToProceed);

        $this->grabUsefulUrls($html);

        /** @var AbstractType $type */
        foreach ($this->getTypes()->items() as $type) {
            $type->setParser($this);
            $type->setHtml($html);
            $type->setUrl($urlToProceed);
            $data = $type->grabPageData();

            if ($data->isEmpty()) {
                continue;
            }
            
            $type->save();
        }
    }

    public function grabUsefulUrls($html)
    {
        $sniffer = app()->make(
            Sniffer::class, [$this->getClient(), $html]
        );
        $sniffer->proceed();
    }

    protected function getUrlToProcess()
    {
        return $this->getUrlsPipeline()->getNextUrl();
    }

    /**
     * @return Xparser
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     *
     * @return Parser
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     *
     * @return Parser
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return UrlPipeline
     */
    public function getUrlsPipeline()
    {
        return $this->urlsPipeline;
    }

    /**
     * @param mixed $urlsPipeline
     *
     * @return Parser
     */
    public function setUrlsPipeline($urlsPipeline)
    {
        $this->urlsPipeline = $urlsPipeline;

        return $this;
    }

    /**
     * @return TypeInstancesCollection
     */
    public function getTypes()
    {
        if (empty($this->types)) {
            return $this->types
                = new TypeInstancesCollection($this->client);
        }

        return $this->types;
    }


}