<?php


namespace Xparser\Parsers\Configs;


interface ConfigInterface
{
    /**
     * @return callable[][] Where key is a subclass of AbstractType and value is an array of its fields
     */
    public function fields();
    public function setHtml($html);
}