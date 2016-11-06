<?php


namespace Xparser\Url;


use Xparser\Types\TypeInstancesCollection;

class UrlPatternsCollection
{
    protected $items;
    
    /**
     * UrlPatternsCollection constructor.
     *
     * @param TypeInstancesCollection $typeInstancesCollection
     */
    public function __construct(TypeInstancesCollection $typeInstancesCollection)
    {
        $this->typeInstances = $typeInstancesCollection;
    }
    public function all()
    {
        if(empty($this->items)) {
            return $this->collectUrlsSchemas();
        }

        return $this->items;
    }

    protected function collectUrlsSchemas()
    {
        $this->items = [];

        foreach ($this->typeInstances->items() as $typeItem) {
            $this->items[] = $typeItem->urlPatterns();
        }

        return array_flatten($this->items);
    }

}