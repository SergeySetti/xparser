<?php


namespace Xparser\Types;


use Xparser\Parsers\Configs\ConfigInterface;
use Xparser\Parsers\HttpClient;

/**
 * @property ConfigInterface siteConfig
 */
abstract class AbstractType
{
    /**
     * @var \Eloquent $model
     */
    public $site;
    public $url;
    public $html;

    public $fields;

    /**
     * Must save extracted data to the DB.
     *
     * @param \Illuminate\Support\Collection $data
     * @return bool
     */
    abstract public function save($data);

    /**
     * Must return all nodes which must be processed
     * using the fields parser.
     *
     * @return mixed
     */
    abstract public function all();
    
    public function __construct(ConfigInterface $siteConfig, $url)
    {
        $this->siteConfig = $siteConfig;
        $this->html = $this->loadHtml($url);
        $this->url = $url;
        $this->siteConfig->setHtml($this->html);
    }

    /**
     * @param $url
     * @return string
     */
    public function loadHtml($url)
    {
        return HttpClient::getHtmlByUrl($url);
    }

    public function extract($fields)
    {
        foreach ($this->all() as $node) {
            $this->extractOneAndSave($node, $fields);
        }
    }

    public function extractOneAndSave($node, $fields)
    {
        if ($this->shouldNotSkip($node, $fields)) {
            $data = $this->extractData($node, $fields);
            $data->put('url', $this->url);
            $this->save($data);
        }
    }

    /**
     * Should we don't skip and save this node?
     *
     * @param $node
     * @param callable[] $fields
     * @return bool
     */
    private function shouldNotSkip($node, $fields)
    {
        /** @var \Closure $parseSkip */
        $parseSkip = array_get($fields, 'skip');

        return ! ($parseSkip && $parseSkip($node));
    }

    /**
     * Extracts data from the node and converts the
     * `fieldName => parser` mapping to the
     * `fieldName => fieldValue`.
     *
     * @param $node
     * @param callable[] $fields
     * @return \Illuminate\Support\Collection
     */
    private function extractData($node, $fields)
    {
        return collect($fields)->map(function($parse) use ($node) {
            return $parse($node);
        });
    }
}