<?php


namespace Xparser;


use Illuminate\Support\Collection;
use Xparser\Parsers\Parser;
use Xparser\Url\Url;

abstract class AbstractType
{

    /**
     * @var string
     */
    protected $html;
    /**
     * @var Url
     */
    protected $url;
    /**
     * @var Xparser
     */
    protected $client;
    /**
     * @var Parser
     */
    protected $parser;

    abstract public function urlPatterns();

    /**
     * @return Collection
     */
    abstract public function fields();

    /**
     * @return Collection
     */
    abstract public function save();

    /**
     * @param Url $urlToProceed
     */
    public function setUrl($urlToProceed)
    {
        $this->url = $urlToProceed;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function html()
    {
        return $this->html;
    }

    public function extractField($field)
    {
        return call_user_func($field);
    }

    protected function isPageForTheType() : bool
    {
        foreach ($this->urlPatterns() as $pattern) {
            if (preg_match('/' . $pattern . '/i', $this->url->url())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function grabPageData()
    {
        if ($this->isPageForTheType()) {
            return $this->grabFields();
        }

        return collect();
    }

    /**
     * @return Collection
     */
    protected function grabFields()
    {
        $data = collect();

        foreach ($this->fields() as $name => $field) {
            $data->put($name, $this->extractField($field));
        }

        return $data;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Xparser
     */
    public function getClient(): Xparser
    {
        return $this->client;
    }

    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        return $this->parser;
    }

}