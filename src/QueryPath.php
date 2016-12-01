<?php


namespace Xparser;


use QueryPath\DOMQuery;

class QueryPath extends \QueryPath
{

    public static function qp($source = null, $selector = null, $options = [])
    {
        // $defaultOptions = ['convert_from_encoding' => 'auto',];
        $defaultOptions = [];
        $options = array_merge($defaultOptions, $options);
        return parent::withHTML($source, $selector, $options);
    }
    
}