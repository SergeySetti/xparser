<?php


namespace packages\sergeysetti\xparser\tests\Sniffer;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use SergeySetti\Xparser\Parsers\Sniffer;
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
            Sniffer::class, [$client, $this->htmlSample]
        );

        $sniffer->proceed();

        $this->seeInDatabase('xparser_urls', ['url' => '/&page=123']);
    }
}