<?php


namespace Xparser\Parsers;


use GuzzleHttp\Client;
use Xparser\Url\Url;

class Page
{
    /**
     * Returns HTML of page from the given URL.
     *
     * @param $url
     * @return string
     */
    public static function getHtmlByUrl(Url $url)
    {
        $client = app(Client::class, [
            'base_uri' => $url->url(),
            'timeout'  => 55.0,
            'curl'  => [
                CURLOPT_ENCODING => "gzip, deflate",
            ],
        ]);

        $response = $client->request('GET', $url);
        return $response->getBody()->getContents();
    }
}