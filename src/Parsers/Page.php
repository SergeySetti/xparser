<?php


namespace SergeySetti\Xparser\Parsers;


use GuzzleHttp\Client;

class Page
{
    public static function getByUrl($url, $params = [])
    {
        $client = app(Client::class, [
            'base_uri' => $url,
            'timeout'  => 10.0,
        ]);

        $response = $client->request('GET', $url, ['query' => $params]);
        return $response->getBody()->getContents();
    }
}