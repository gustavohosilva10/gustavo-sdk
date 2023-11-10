<?php
namespace Zoop\Transfers;

use Zoop\Zoop;

#TRANSFERENCIAS DO USUÁRIO
class Transfers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    #LISTA AS TRANSFERENCIAS DO VENDEDOR
    public function getTransfers($sellerId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/'. $sellerId .'/transfers'
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

    #LISTA TRANSFERENCIAS DO MARKETPLACE
    public function getAllTransfers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers'
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

    #DETALHES DE UMA TRANSFERÊNCIA
    public function getTransfer($transferId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers/'. $transferId
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

    #TRANSAÇÕES DA TRANSFERÊNCIA
    public function getTransactions($transferId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers/'. $transferId .'/transactions'
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