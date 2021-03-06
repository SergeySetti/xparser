<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\AbstractType;
use Xparser\QueryPath;

class TypeStub extends AbstractType
{
    public function urlPatterns()
    {
        return [
            '\/wiki\/[a-zA-Z]+$',
            '\/&page=[0-9]+',
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

    public function save()
    {
        return true;
    }

}
