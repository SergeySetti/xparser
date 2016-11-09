<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\AbstractType;
use Xparser\QueryPath;

class TypeForSnifferStub extends AbstractType
{
    public function urlPatterns()
    {
        return [
            '\/wiki\/[a-zA-Z]+'
        ];
    }

    public function fields()
    {
        return collect([
            'name' => function () {
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
