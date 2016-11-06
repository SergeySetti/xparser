<?php


namespace Xparser\Tests\Parsers;


use Xparser\Parsers\Parser;
use Xparser\Parsers\ParserBuilder;
use Xparser\Tests\Clients\Stubs\SomeClientStub;

class ParserBuildingTest extends \TestCase 
{

    public function testParserBuilding()
    {
        $client = new SomeClientStub();
        $builder = new ParserBuilder($client);
        $parser = $builder->build();

        $this->assertInstanceOf(Parser::class, $parser);
    }
}