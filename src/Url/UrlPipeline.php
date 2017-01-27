<?php


namespace Xparser\Url;


use Xparser\Xparser;

class UrlPipeline
{
    /**
     * @var Xparser
     */
    protected $client;

    /**
     * @var UrlModel
     */
    protected $urlModel;
    
    public function getNextUrl()
    {
        return $this->getUrlModel()->chooseNext(
            $this->getClient()->getParser()->getSite()
        );
    }

    /**
     * @param Xparser $client
     */
    public function setClient(Xparser $client)
    {
        $this->client = $client;
    }

    /**
     * @param UrlModel $urlModel
     */
    public function setUrlModel(UrlModel $urlModel)
    {
        $this->urlModel = $urlModel;
    }

    /**
     * @return UrlModel
     */
    public function getUrlModel(): UrlModel
    {
        if (isset($this->urlModel)) {
            return $this->urlModel;
        }

        return new UrlModel();
    }

    /**
     * @return Xparser
     */
    public function getClient(): Xparser
    {
        return $this->client;
    }
}