<?php


namespace packages\sergeysetti\xparser\tests\Url;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Xparser\Parsers\ParserBuilder;
use Xparser\Site\Site;
use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Url\UrlPipeline;

class UrlPipelineTest extends \TestCase 
{
    
    use DatabaseMigrations;
    use DatabaseTransactions;
    
    public function testThatOnEmptyDbForClientReturnedMainClientSiteUrl()
    {
        $client = new SomeClientStub();
        $builder = new ParserBuilder($client);
        $parser = $builder->build();
        
        $siteMock = \Mockery::mock(Site::class);
        $siteMock->shouldReceive('getKey')->andReturn(123);
        $siteMock->shouldReceive('getUrl')->andReturn('http://wikipedia.org/');
        $parser->setSite($siteMock);
        $client->setParser($parser);
        
        $pipeline = app()->make(UrlPipeline::class, [$client]);

        $this->assertEquals($pipeline->getNextUrl()->url(), 'http://wikipedia.org/');
    }

}