<?php


namespace Xparser;


use Illuminate\Support\Collection;

abstract class AbstractType
{
    protected $html;

    abstract public function urlPatterns();

    /**
     * @return Collection
     */
    abstract public function fields();

    protected function collectFields()
    {

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

}