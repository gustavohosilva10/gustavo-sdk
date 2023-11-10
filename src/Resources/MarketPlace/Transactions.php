<?php
namespace Zoop\Marketplace;

use Zoop\Zoop;

#TRANSAÇÕES DO VENDEDOR. PODE SER USADA PARA CONSULTAR BOLETOS
class Transactions extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    #BUSCA TODOS OS DADOS DO VENDEDOR
    public function getAllTransactions()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/' . $this->configurations['auth']['on_behalf_of'] .'/transactions'
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

    #PEGA DADOS ATRAVES DO ID DA TRANSAÇÃO
    public function getTransaction($transaction)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions/'. $transaction
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