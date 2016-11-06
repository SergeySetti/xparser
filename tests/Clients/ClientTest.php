<?php


namespace Xparser\Tests\Clients;

 
use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Tests\Clients\Stubs\SomeOtherClientStub;
use Xparser\Xparser;

class ClientTest extends \TestCase 
{
    public function testClientNamesHashes()
    {
        /** @var Xparser $client1 */
        $client1 = app()->make(SomeClientStub::class);
        /** @var Xparser $client2 */
        $client2 = app()->make(SomeOtherClientStub::class);
        
        $this->assertNotEquals(
            $client1->getClientKey(),
            $client2->getClientKey()
        );
    }
    
}