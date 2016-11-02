<?php


namespace SergeySetti\Xparser\Parsers;


use Illuminate\Support\Collection;
use Xparser\Site;

/**
 * @property Site site
 */
class Patterns
{
    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return Collection::make(
            $this->site->urlSchemes->pluck('pattern')->toArray()
        );
    }
}