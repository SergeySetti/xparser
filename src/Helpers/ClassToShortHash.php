<?php


namespace Xparser\Helpers;


class ClassToShortHash
{

    public static function convert($str)
    {
        return crc32($str);
    }
}