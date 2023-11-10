<?php
namespace Zoop\MarketPlace;

use Zoop\Zoop;

#USUARIOS DO MARKET PLACE
class Sellers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    #PEGA VENDEDOR PELO ID
    public function getSeller($sallerId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/'. $sallerId
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }

    #LISTA TODOS OS VENDEDO
    public function getAllSellers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers'
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }
}