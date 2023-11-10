<?php
namespace Exemples;
use Zoop\Core\ZoopConfig;
use Zoop\ZoopClient;
require("vendor/autoload.php");

class Exemples extends Zoop 
{
    private $token;
    private $marketplace;
    private $seller;

    public function __construct($token,$marketplace,$seller)
    {
        $this->token = env('tokenAdm');
        $this->marketplace = env('marktplace');
        $this->seller = env('seller');

        $client = new ZoopClient(
            ZoopConfig::configure($token, $marketplace, $vendedor)
        );
    }

    public function createBuyer(Request $request)
    {
        try {
            $buyer = $this->client->createBuyer([
                'first_name' => 'nome',
                'taxpayer_id' => 'CPJ', /* CPF */
                'email' => 'EMAIL',
                    'address' => [
                        'line1' => 'Rua',
                        'line2' => 'RUA',
                        'neighborhood' => 'BAIRRO',
                        'city' => 'CIDADE',
                        'state' => 'ESTADO',
                        'postal_code' => 'CEP',
                        'country_code' => 'BR'
                    ],
                ]);
                print_r($buyer);
            } catch(\Exception $e){
                echo $e->getMessage() . PHP_EOL;
        }
    }

    public function datailsBuyer($idBuyer)
    {
        try {
            $comprador = $this->client->getBuyer('5345634635');
            print_r($comprador);
        } catch(\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function createBoleto(Request $request)
    {
        try {
            $bolet = $this->client->generateTicket(array(
                'amount' => 4950, #quantia valor
                'logo' => 'https://dashboard.zoop.com.br/assets/imgs/logo-zoop.png',
                'description' => 'Pagamento teste',
                'top_instructions' => 'Instruções de pagamento',
                'body_instructions' => 'Não receber após a data de vencimento.',
                'expiration_date' => (string)date('Y-m-d'),
                'payment_limit_date' => (string)date('Y-m-d'),
                'late_fee' => [
                    'mode' => 'PERCENTAGE',
                    'percentage' => 2
                ],
                'interest' => [
                    'mode' => 'MONTHLY_PERCENTAGE',
                    'percentage' => 1,
                    'start_date' => (string)date('Y-m-d'),
                ],
                'discount' => [
                    'mode' => 'FIXED',
                    'amount' => 100,
                    'limit_date' => (string)date('Y-m-d'),
                ],
            ),  'ID_DO_COMPRADOR', 'SEU_ID_VENDA');
            print_r($bolet);
        } catch(\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function createWebook()
    {
        try {
            $webhook = $this->client->createWebHook('https://gustavo.com.br', 'WebHook');
            print_r($webhook);
        } catch (\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function listWebook()
    {
        try {
            $webhooks = $this->client->getAllWebHooks();
            print_r($webhooks);
        } catch (\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }


    public function getDataTransaction($idTransaction)
    {
        try {
            $transactions = $client->getTransaction($idTransaction);
            print_r($transactions);
        } catch(\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function detailsSeller($idSeller)
    {
        try {
            $vendedor = $this->client->getSeller($idSeller);
            print_r($vendedor);
        } catch(\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public  function litTransactionSeller($idSeller)
    {
        try {
            $transactions = $this->client->getTransfers($sellerId);
            print_r($transactions);
        } catch(\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function handleEventsWebhook(Request $request)
    {
        try {
            $webHookAlert = $this->client->webHookListen();
            if (isset($webHookAlert) && !empty($webHookAlert) && is_array($webHookAlert)) {
                $log = fopen('webhook.json', 'a+');
                fwrite($log, json_encode($webHookAlert));
                fclose($log);
            } else {
                echo 'o evento recebido não é valido';
            }
        } catch (\Exception $e){
            echo $e->getMessage() . PHP_EOL;
        }
    }
   
}