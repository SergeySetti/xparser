<?php


namespace Xparser\Parsers;

use Illuminate\Support\Collection;
use Xparser\Site\Site;
use Xparser\Types\TypeInstancesCollection;
use Xparser\Url\UrlPipeline;
use Xparser\Xparser;

class Parser
{
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

    /**
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
        if (empty($this->types )) {
            return $this->types
                = new TypeInstancesCollection($this->client);         
        }

        return $this->types;
    }
    

}