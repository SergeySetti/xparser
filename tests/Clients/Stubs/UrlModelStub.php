<?php


namespace Xparser\Tests\Clients\Stubs;


use Xparser\Url\UrlModel;

class UrlModelStub extends UrlModel
{

    public static function create(array $attributes = Array())
    {
        return new \Eloquent();
    }

    public function update(array $attributes = Array(), array $options = Array())
    {
        return new \Eloquent();
    }
}