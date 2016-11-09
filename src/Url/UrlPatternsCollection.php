<?php


namespace Xparser\Url;


use Illuminate\Support\Collection;
use Xparser\Types\TypeInstancesCollection;

class UrlPatternsCollection
{
    /**
     * @var Collection
     */
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

    /**
     * @return Collection
     */
    public function all()
    {
        if(empty($this->items)) {
            return $this->collectUrlsSchemas();
        }

        return $this->items;
    }

    /**
     * @return Collection
     */
    protected function collectUrlsSchemas()
    {
        $this->items = [];

        foreach ($this->typeInstances->items() as $typeItem) {
            $this->items[] = $typeItem->urlPatterns();
        }

        return collect(array_flatten($this->items));
    }

}