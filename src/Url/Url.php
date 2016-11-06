<?php


namespace Xparser\Url;



class Url
{
    protected $url;

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Url constructor.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public static function chooseNext()
    {
        // $nextUrl = UrlModel::where()
    }
}