<?php
namespace Zoop\Payment;

use Zoop\Zoop;

#Gerar boletos e extrair dados
class Ticket extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    #PREPARA BOLETO E MONTA OS DADOS
    private function prepareTicket(array $ticket, $userId)
    {
        return [
            'amount' => $ticket['amount'],
            'currency' => 'BRL',
            'logo' => array_key_exists('logo', $ticket) ? $ticket['logo'] : null,
            'description' => $ticket['description'],
            'payment_type' => 'boleto',
            'payment_method' => [
                'top_instructions' => $ticket['top_instructions'],
                'body_instructions' => $ticket['body_instructions'],
                'expiration_date' => $ticket['expiration_date'],
                'payment_limit_date' => $ticket['payment_limit_date'],
                'billing_instructions' => [
                    'late_fee' => array_key_exists('late_fee', $ticket) ? $ticket['late_fee'] : null,
                    'interest' => array_key_exists('interest', $ticket) ? $ticket['interest'] : null,
                    'discount' => array_key_exists('discount', $ticket) ? $ticket['discount'] : null,
                ],
            ],
            'capture' => false,
            'on_behalf_of' => $this->configurations['auth']['on_behalf_of'],
            'source' => [
                'usage' => 'single_use',
                'type' => 'customer',
                'capture' => false,
                'on_behalf_of' => $this->configurations['auth']['on_behalf_of']
            ],
            'customer' => $userId,
        ];
    }

    #MANDA BOLETO PARA PROCESSAR  E MOSTRAR DADOS DE VALOR
    private function processTicket(array $ticket, $userId, $referenceId = null)
    {
        if(!is_null($referenceId)){
            $ticket['reference_id'] = $referenceId;
        }
        try {
            $ticket = $this->prepareTicket($ticket, $userId);
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions', 
                ['json' => $ticket]
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return [
                    'id' => $response['id'],
                    'ticketId' => $response['payment_method']['id'],
                    'status' => $response['status'],
                ];
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }

    #GERA BOLETO E RETORNA URL PARA BAIXAR PDF 
    public function generateTicket(array $ticket, $userId, $referenceId = null)
    {
        try {
            $generatedTicket = $this->processTicket($ticket, $userId, $referenceId);
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/boletos/' . $generatedTicket['ticketId']
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return array(
                    'payment' => array(
                        'id' => $generatedTicket['id'],
                        'ticketId' => $generatedTicket['ticketId'],
                        'url' => $response['url'],
                        'barcode' => $response['barcode'],
                        'status' => $generatedTicket['status']
                    ),
                    'userId' => $userId
                );
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }
}