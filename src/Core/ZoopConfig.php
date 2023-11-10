<?php
namespace Zoop\Core;

class ZoopConfig
{
    public static function configure($token, $marketplace, $vendedor, $is_zend = null)
    {
        $configurations = [
            'marketplace' => $marketplace,
            'gatway' => 'zoop',
            'base_url' => 'https://api.zoop.ws',
            'auth' => [
                'on_behalf_of' => $vendedor,
                'token' => $token
            ],
            'configurations' => [
                'limit' => 20,
                'sort' => 'time-descending',
                'offset' => 0,
                'date_range' => null,
                'date_range[gt]' => null,
                'date_range[gte]' => null,
                'date_range[lt]'=> null,
                'date_range[lte]' => null,
                'reference_id'=> null,
                'status' => null,
                'payment_type' => null,
            ],
            'guzzle' => [
                'base_uri' => 'https://api.zoop.ws',
                'timeout' => 10,
                'headers' => [
                    'Authorization' => 'Basic ' . \base64_encode($token . ':')
                ]
            ]
        ];
        return self::ClientHelper($configurations, $is_zend);
    }

    private static function ClientHelper(array $configurations, $is_zend = null)
    {
        $client = $configurations['guzzle'];
        unset($configurations['guzzle']);
        if(\is_null($is_zend)){
            $configurations['guzzle'] = new \GuzzleHttp\Client($client);
        } else {
            $configurations['guzzle'] = new \ZendAdapter\ZendRequest($client);
        }
        return $configurations;
    }
}