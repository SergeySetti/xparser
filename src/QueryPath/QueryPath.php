<?php


namespace SergeySetti\Xparser\QueryPath;


class QueryPath extends \QueryPath
{

    public static function withHTML($source = null, $selector = null, $options = []) 
    {
        $defaultOptions = ['convert_from_encoding' => 'pass',];
        $options = array_merge($defaultOptions, $options);
        return parent::withHTML($source, $selector, $options);
    }
    
}