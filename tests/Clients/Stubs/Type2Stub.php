<?php


namespace Xparser\Tests\Clients\Stubs;


use \Xparser\AbstractType;
use \Xparser\QueryPath;

class Type2Stub extends AbstractType
{
    public function urlPatterns()
    {
        return [
            '\/city\.htm',
        ];
    }

    public function fields()
    {
        return collect([
            'city' => function() {
                return QueryPath::qp($this->html(), 'div')
                                ->text();
            },
        ]);
    }
}
