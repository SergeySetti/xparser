<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\Xparser;

class ClientForSnifferStub extends Xparser
{
    protected $siteUrl = 'http://wikipedia.org';

    protected $urlsToCrawl = [
        '\/&page=[0-9]+',
    ];

    public function registerTypes()
    {
        return [
            TypeForSnifferStub::class,
        ];
    }
}