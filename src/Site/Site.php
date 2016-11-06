<?php


namespace Xparser\Site;


use Xparser\Helpers\ClassToShortHash;
use Xparser\Xparser;

class Site
{

    /**
     * Site constructor.
     *
     * @param Xparser $client
     */
    public function __construct(Xparser $client)
    {
        $this->client = $client;
    }

    public function getUrl()
    {
        return $this->client->siteUrl();
    }

    public function getKey()
    {
        return ClassToShortHash::convert(get_class($this->client));
    }
}