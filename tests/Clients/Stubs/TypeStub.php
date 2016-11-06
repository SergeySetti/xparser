<?php


namespace Xparser\Tests\Clients\Stubs;


use \Xparser\AbstractType;
use \Xparser\QueryPath;

class TypeStub extends AbstractType
{
    public function urlPatterns()
    {
        return [
            '\/person\.rme\/perfid=[a-z-_]+\/gender=f\/[a-z-_]+\.htm',
        ];
    }

    public function fields()
    {
        return collect([
            'name' => function() {
                return QueryPath::qp($this->html(), 'h2')
                                ->text();
            },
        ]);
    }
}
