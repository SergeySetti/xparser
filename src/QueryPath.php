<?php


namespace Xparser;


class QueryPath extends \QueryPath
{

    /**
     * @param null $source
     * @param null $selector
     * @param array $options
     *
     * @return mixed
     */
    public static function qp($source = null, $selector = null, $options = [])
    {
        $defaultOptions = ['convert_from_encoding' => 'pass',];
        $options = array_merge($defaultOptions, $options);
        return parent::withHTML($source, $selector, $options);
    }
    
}