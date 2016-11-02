<?php


namespace SergeySetti\Xparser\Parsers\Configs;


use SergeySetti\Xparser\QueryPath\QueryPath;

class Config
{
    public $qp;
    public $html;

    public function __construct()
    {
        $this->qp = new QueryPath();
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

}