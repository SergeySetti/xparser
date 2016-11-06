<?php


namespace Xparser\Url;


use packages\sergeysetti\xparser\src\Url\UrlModel;
use Xparser\Xparser;

class UrlPipeline
{
    
    /**
     * UrlPipeline constructor.
     *
     * @param Xparser $client
     * @param \Xparser\Url\UrlModel $urlModel
     */
    public function __construct(Xparser $client, \Xparser\Url\UrlModel $urlModel)
    {
        $this->client = $client;
        $this->urlModel = $urlModel;
    }

    public function getNextUrl()
    {
        return $this->urlModel->chooseNext(
            $this->client->getParser()->getSite()
        );
    }
}