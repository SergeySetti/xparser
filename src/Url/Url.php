<?php


namespace Xparser\Url;


class Url
{
    protected $url;
    protected $baseUrlStr;

    /**
     * @return string
     */
    public function url(): string
    {
        if (! empty($this->baseUrlStr)) {
            if (substr($this->url, 0, 4) !== 'http') {
                $this->url =
                    trim($this->baseUrlStr, " \t\n\r\0\x0B\\") . $this->url;
            }
        }

        return $this->url;
    }

    /**
     * Url constructor.
     *
     * @param string $url
     * @param string $baseUrlStr
     */
    public function __construct($url, $baseUrlStr = '')
    {
        $this->url        = $url;
        $this->baseUrlStr = $baseUrlStr;
    }

}