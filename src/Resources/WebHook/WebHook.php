<?php
namespace Zoop\WebHook;

use Zoop\Zoop;

# GERENCIA EVENTOS 
class WebHook extends Zoop 
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
        $this->configurations = $configurations;
    }

    #VALIDA RESPOSTA
    private function validatePayload(string $payload)
    {
        $payload = \json_decode($payload, TRUE);
        if(isset($payload)
            && !empty($payload)
            && \is_array($payload)
        ) {
            if (isset($payload['id'])
                && isset($payload['type'])
                && isset($payload['resource'])
                && isset($payload['payload'])
                && isset($payload['payload']['object']['status'])
            ) {
                return $payload;
            }
            return false;
        }
        return false;
    }

    #FORMATA O PAYLOAD DA RESPOSTA
    private function resumePayload(array $payload)
    {
        $payloadReturn = array(
            'event' => array(
                'id' => $payload['id'],
                'type' => $payload['type']
            ),
            'payment' => array(
                'id' => $payload['payload']['object']['id'],
                'type' => $payload['payload']['object']['payment_type'],
                'amount' => $payload['payload']['object']['amount'],
            )
        );
        if(isset($payload['payload']['object']['reference_id'])
            && !empty($payload['payload']['object']['reference_id'])){
                $payloadReturn['referenceId'] = $payload['payload']['object']['reference_id'];
        }
        return $payloadReturn;
    }


    #VALIDA COMO EVENTO VALIDO
    public function webHookListen()
    {
        $payload = \file_get_contents('php://input');
        $payload = $this->validatePayload($payload);
        if($payload && is_array($payload)){
            $payload = $this->resumePayload($payload);
            return $payload;
        }
  
        return false;
    }

    #CRIA WEBHOOK
    public function createWebHook($url, $description)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks',
                ['json' => array(
                    'url' => $url,
                    'method' => 'POST', 
                    'description' => $description
                )]
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

    #LISTA WEB HOOK
    public function getAllWebHooks()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks'
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

    #DELETE WEB HOOK ESPECIFICO
    public function deleteWebHook($webhookId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'DELETE', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks/' . $webhookId
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