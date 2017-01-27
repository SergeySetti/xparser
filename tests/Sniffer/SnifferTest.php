<?php


namespace Xparser\Tests\Sniffer;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Xparser\Parsers\Sniffer;
use Xparser\Tests\Clients\Stubs\ClientForSnifferStub;
use Xparser\Tests\Clients\TypeTest;

class SnifferTest extends TypeTest
{

    use DatabaseMigrations;
    use DatabaseTransactions;

    private $htmlSample = '
    <ul>
        <li><a href="/docs/5.3/testing">Getting Started</a></li>
        <li><a href="/docs/5.3/application-testing">Application Testing</a></li>
        <li><a href="/wiki/abc">Database</a></li>
        <li><a href="/&page=123">Mocking</a></li>
    </ul>
    ';

    public function testSniffersWork()
    {
        $client = app()->make(ClientForSnifferStub::class);

        $sniffer = app()->make(
            Sniffer::class
        );
        
        $sniffer->setClient($client);
        $sniffer->setHtml($this->htmlSample);

        $sniffer->proceed();

        $this->assertDatabaseHas('xparser_urls', ['url' => '/&page=123']);
    }
}