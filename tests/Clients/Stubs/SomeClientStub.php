<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\Xparser;

class SomeClientStub extends Xparser 
{
    protected $siteUrl = 'http://wikipedia.org';

    protected $urlsToCrawl = [
        '\/&item=[0-9]+',
    ];

    public function registerTypes()
    {
        return [
            TypeStub::class,
        ];
    }
}