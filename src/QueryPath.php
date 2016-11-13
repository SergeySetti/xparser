<?php


namespace Xparser;


class QueryPath extends \QueryPath
{

    public static function qp($source = null, $selector = null, $options = [])
    {
        $defaultOptions = [];
        $options = array_merge($defaultOptions, $options);
        return parent::withHTML($source, $selector, $options);
    }
    
}