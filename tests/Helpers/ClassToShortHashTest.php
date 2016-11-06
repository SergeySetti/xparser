<?php


namespace Tests;


use App\XparserClients\PlClient;
use TestCase;
use Xparser\Helpers\ClassToShortHash;
use Xparser\Tests\Clients\Stubs\SomeClientStub;

class ClassToShortHashTest extends TestCase
{
    public function testHashing()
    {
        $client = new SomeClientStub();
        $name = get_class($client);
        $hash1 = ClassToShortHash::convert($name);
        $hash2 = ClassToShortHash::convert($name.'a');
        
        $this->assertNotEmpty($hash1);
        $this->assertNotEmpty($hash2);
        $this->assertNotEquals($hash1, $hash2);
    }

}
