<?php


namespace Xparser\Tests\Clients;


use Xparser\Tests\Clients\Stubs\TypeStub;

class TypeTest extends \TestCase
{
    public function testThatFieldCanParseSomeData()
    {
        /** @var TypeStub $type */
        $type = new TypeStub();
        $type->setHtml('<article>
			<header>
				<h2>Article</h2>
				<p>Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="http://example.com/">Writer</a></p>
			</header>
			<p>Doll</p>
		</article>');
        $result = $type->extractField($type->fields()->first());
        $this->assertEquals('Article', $result);

    }


}

