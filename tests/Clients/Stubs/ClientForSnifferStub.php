<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\Xparser;

class ClientForSnifferStub extends Xparser
{
    /*
     * Provide the main url for website destination
     * */
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