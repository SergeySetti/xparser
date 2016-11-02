<?php


namespace SergeySetti\Xparser\Types;


use Illuminate\Support\Collection;
use SergeySetti\Xparser\Parsers\Configs\ConfigInterface;

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
    
    public function __construct(ConfigInterface $siteConfig, $url)
    {
        $this->siteConfig = $siteConfig;
        $this->html = Page::getByUrl($url);
        $this->url = $url;
        $this->siteConfig->setHtml($this->html);
    }
    
    public function fields(ConfigInterface $siteConfig)
    {
        return $siteConfig->fields();
    }

    public function extract($type)
    {
        $data = collect();
        
        $fields = $this->fields($this->siteConfig)[$type];
        
        $allNodes = $this->all();

        foreach ($allNodes as $node) {
            $data->push($this->extractOne($node, $fields));
        }
        
        return $data;
    }

    public function extractOne($node, $fields)
    {
        $data = new Collection();
        $data->put('url', $this->url);
        
        foreach($fields as $fieldName => $field) {

            $result = call_user_func($field, $node);

            $data->put(
                $fieldName,
                $result
            );
        }

        $this->save($data);

        return $data;
    }
    
    /**
     * @param Collection $data
     * @return bool
     */
    abstract public function save($data);
    abstract public function all();
    
}