<?php


namespace Xparser\Tests\Url;


use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Types\TypeInstancesCollection;
use Xparser\Url\UrlPatternsCollection;

class UrlPatternsCollectionTest extends \TestCase
{

    public function testThatPatternsCollectionReturnUrlPatterns()
    {
        $client        = $this->app->make(SomeClientStub::class);
        $typeInstances = new TypeInstancesCollection($client);

        $urlPatterns = new UrlPatternsCollection($typeInstances);
        $patterns    = $urlPatterns->all();
        $this->assertNotEmpty($patterns);
    }
}