<?php


namespace Xparser\Tests\Clients;


use Xparser\AbstractType;
use Xparser\Tests\Clients\Stubs\SomeClientStub;
use Xparser\Tests\Clients\Stubs\Type2Stub;
use Xparser\Tests\Clients\Stubs\TypeStub;
use Xparser\Tests\Clients\Stubs\TypeStubWithPersonPattern;
use Xparser\Tests\Clients\Stubs\TypeStubWithutUrlsPatterns;
use Xparser\Url\Url;
use Xparser\Xparser;

class TypeTest extends \TestCase
{
    public function testThatFieldCanParseSomeData()
    {
        /** @var TypeStub $type */
        $type = new TypeStub();
        $type->setHtml(app()->make(HtmlSample::class)->getSample1());
        $result = $type->extractField($type->fields()->first());
        $this->assertEquals('Article', $result);

    }

    public function testPageUrlMatchesIfItsActuallyIs()
    {
        $type = $this->prepareType(
            TypeStubWithPersonPattern::class,
            'https://en.wikipedia.org/wiki/Ukraine',
            app()->make(HtmlSample::class)->getSample2()
        );

        $data = $type->grabPageData();

        $this->assertEquals($data->get('city'), 'Detroit');
        $this->assertEquals($data->get('name'), 'Christine');
    }

    public function testPageUrlNotMatchesIfItsActuallyNot()
    {
        $type = $this->prepareType(
            TypeStubWithutUrlsPatterns::class,
            'https://en.wikipedia.org/wiki/Ukraine',
            app()->make(HtmlSample::class)->getSample2()
        );

        $data = $type->grabPageData();

        $this->assertEquals($data->get('city'), null);
    }

    /**
     * @param $typeClass
     * @param $url
     * @param $html
     *
     * @return AbstractType
     */
    protected function prepareType($typeClass, $url, $html)
    {
        $client = app()->make(SomeClientStub::class);
        $parser = Xparser::create($client);

        $type = app()->make($typeClass);
        $type->setClient($client);
        $type->setParser($parser);
        $type->setUrl(new Url($url));
        $type->setHtml($html);

        return $type;
    }

}


class HtmlSample
{
    public function getSample1()
    {
        return '<article>
			<header>
				<h2>Article</h2>
				<p>Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="http://example.com/">Writer</a></p>
			</header>
			<p>Doll</p>
		</article>';
    }

    public function getSample2()
    {
        return '<div class="country">
            <h2 class="city">Detroit</h2>
            <h3 class="name">Christine</h3>
            <p>Text (57)</p>
            <br>
            </div>';
    }
}