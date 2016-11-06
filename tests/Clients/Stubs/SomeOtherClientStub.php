<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\Xparser;

class SomeOtherClientStub extends Xparser 
{
    protected $siteUrl = 'http://wikipedia.org';

    public function registerTypes()
    {
        return [
            TypeStub::class,
        ];
    }
}