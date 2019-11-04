<?php


namespace Xparser\Types;


use Xparser\Xparser;

class TypeInstancesCollection
{
    protected $items;

    /**
     * TypeInstancesCollection constructor.
     *
     * @param Xparser $client
     */
    public function __construct(Xparser $client)
    {
        $this->client = $client;
    }

    public function items()
    {
        if(empty($this->items)) {
            return $this->collectTypeInstances();
        }
        
        return $this->items;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function collectTypeInstances()
    {
        $this->items = [];

        foreach ($this->client->registerTypes() as $type) {
            $this->items[] = app()->get($type) ;
        }

        return collect($this->items);
    }

}
