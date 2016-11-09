<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\AbstractType;
use Xparser\QueryPath;

class TypeStubWithutUrlsPatterns extends AbstractType
{
    public function urlPatterns()
    {
        return [

        ];
    }

    public function fields()
    {
        return collect([
            'city' => function () {
                return QueryPath::qp($this->html(), '.city')
                                ->text();
            },
            'name' => function () {
                return QueryPath::qp($this->html(), 'h3')
                                ->text();
            },
        ]);
    }

    public function save()
    {
        return true;
    }
}
