<?php


namespace Xparser;


use Illuminate\Support\Collection;
use Xparser\Helpers\ClassToShortHash;
use Xparser\Parsers\Parser;
use Xparser\Parsers\ParserBuilder;
use Xparser\Types\TypeInstancesCollection;
use Xparser\Url\UrlPatternsCollection;

abstract class Xparser implements XparserInterface
{
    protected $instances;
    protected $urlPatterns;
    protected $urlsToCrawl = [];
    protected $siteUrl;
    protected $parser;

    abstract public function registerTypes();

    /**
     * @param Xparser $client
     *
     * @return Parser
     */
    public static function create(Xparser $client)
    {
        $parserBuilder = new ParserBuilder($client);

        return $parserBuilder->build();
    }

    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    public function getClientKey()
    {
        return ClassToShortHash::convert(get_class($this));
    }

    public function siteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @return Collection
     */
    public function getUsefulUrlsPatterns()
    {
        if (empty($this->instances)) {
            $this->instances = new TypeInstancesCollection($this);
        }

        $urlPatterns = new UrlPatternsCollection($this->instances);

        return $urlPatterns->all()->merge($this->urlsToCrawl);
    }

}