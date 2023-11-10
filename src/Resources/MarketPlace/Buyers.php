<?php
namespace Zoop\MarketPlace;

use Zoop\Zoop;

# CLASSE DE COMPRADORES DO MARKETPLACE

class Buyers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    #CRIA USUARIO
    public function createBuyer(array $user)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers', 
                ['json' => $user]
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

    #LISTA USUARIOS
    public function getAllBuyers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers'
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

    #ATUALIZA USURARIO
    public function updateBuyer($userId, array $user)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'PUT', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers/' . $userId,
                ['json' => $user]
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

    #PEGA DADOS DO USUARIO ASSOCIADO AO ID 
    public function getBuyer($userId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers/' . $userId
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

    #APAGA USUARIO
    public function deleteBuyer($userId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'DELETE', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers/' . $userId
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