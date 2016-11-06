<?php


namespace Xparser;


use Xparser\Helpers\ClassToShortHash;
use Xparser\Jobs\Parse;
use Xparser\Parsers\Parser;
use Xparser\Parsers\ParserBuilder;

abstract class Xparser implements XparserInterface
{
    protected $instances;
    protected $urlPatterns;
    protected $siteUrl;
    protected $parser;

    public static function create(Xparser $client)
    {
        $parserBuilder = new ParserBuilder($client);

        return $parserBuilder->build();
    }

    public function parse()
    {
        $job = new Parse($this);
        dispatch($job);
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

    protected function getUrlPatterns()
    {
        if(! empty($this->urlPatterns)) {
            return $this->urlPatterns;
        }

        $this->urlPatterns = [];
        
        foreach ($this->collectTypesInstances() as $typesInstance) {
            /** @var AbstractType $typesInstance */
            $this->urlPatterns[] = $typesInstance->urlPatterns();
        }
        
        return array_flatten($this->urlPatterns);
    }
    
    public function getClientKey()
    {
        return ClassToShortHash::convert(get_class($this));
    }

    public function siteUrl()
    {
        return $this->siteUrl;
    }
    
    abstract public function registerTypes();
    
}