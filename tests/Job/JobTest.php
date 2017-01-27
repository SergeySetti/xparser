<?php


namespace Xparser\Tests\Job;


use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Xparser\HttpClient\HttpClient;
use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Url\Url;
use Xparser\Url\UrlModel;
use Xparser\Xparser;

class JobTest extends \TestCase 
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testOfComplexWork()
    {

        $httpClientMock = \Mockery::mock(HttpClient::class);
        $httpClientMock->shouldReceive('getHtmlByUrl')->withAnyArgs()->andReturn('<ul>
        <li><a href="/docs/5.3/testing">Getting Started</a></li>
        <li><a href="/docs/5.3/application-testing">Application Testing</a></li>
        <li><a href="/wiki/abc">Database</a></li>
        <li><a href="/&page=123">Mocking</a></li>
        <li><a href="/&item=777">Mocking</a></li>
    </ul>');

        $this->app->bind(HttpClient::class, function ($app) use ($httpClientMock) {
            return $httpClientMock;
        });

        $client = new SomeClientStub;
        $parser = Xparser::create($client);

        dispatch($parser);
        
        $this->assertDatabaseHas('xparser_urls', ['url' => '/&page=123']);
        $this->assertDatabaseHas('xparser_urls', ['url' => '/&item=777']);
    }

    public function testThatTakenAnOldestUrl()
    {

        $client = new SomeClientStub;

        $httpClientMock = \Mockery::mock(HttpClient::class);

        $httpClientMock
            ->shouldReceive('getHtmlByUrl')
            ->with(\Mockery::on(function ($argument) use ($client) {
                return
                    $argument instanceof Url &&
                    $client->siteUrl() . 'some_oldest_url' == $argument->url();
            }))->andReturn('...');

        $this->app->bind(HttpClient::class, function ($app) use ($httpClientMock) {
            return $httpClientMock;
        });


        $parser = Xparser::create($client);

        UrlModel::create([
            'url'        => 'some_new_url',
            'site_key'   => $parser->getSite()->getKey(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        UrlModel::create([
            'url'        => 'some_oldest_url',
            'site_key'   => $parser->getSite()->getKey(),
            'updated_at' => Carbon::now()->subYears(10)->toDateTimeString(),
        ]);

        dispatch($parser);
    }
}