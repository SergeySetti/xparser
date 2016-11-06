<?php


namespace Xparser\Tests\Clients;


use Xparser\Parsers\Parser;
use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Xparser;

class AbstractParserBuilderFacade extends \TestCase 
{

    public function testAbstractParserBuilder()
    {
        $parser = Xparser::create(new SomeClientStub());
        $this->assertInstanceOf(Parser::class, $parser);
    }
    
}