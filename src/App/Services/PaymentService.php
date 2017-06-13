<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Operation;
use App\Exceptions\FlowException;
use \Interop\Container\ContainerInterface;
use flowAPI;

class PaymentService extends BaseService
{

    private $logPath;
    private $certPath;
    // private $flowApi;

    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        //Get app log path and cert path
        $this->logPath = $this->container->settings['flow']['logPath'];
        $this->certPath = $this->container->settings['flow']['certPath'];

        // $this->flowApi = new flowAPI();
    }

    /**
    *   Creates a payment order
    */
    public function createOrder(string $company, Order $order)
    {
        $flowConfig = $this->getFlowConfig($company);

        //Make request
        $flowAPI = new flowAPI($flowConfig);
        try {
            $flow_pack = $flowAPI->new_order($order->orderId, $order->amount, $order->concept, $order->payerEmail);
            // We might need to allow the user select the payment method in the future.
            // $flow_pack = $flowAPI->new_order($orderId, $amount, $concept, $payerEmail, $medioPago);
            
            return $flow_pack;
        } catch (Exception $e) {
            throw new MyException('Failed creating flow order '. $e->getMessage());
        }
    }

    /**
    *   Get Url for a given operation
    */
    public function getUrlFor(string $operation)
    {
        $url = '';
        switch ($operation) {
            case Operation::Payment :
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD');
                break;
            case Operation::Return :
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_RETURN') : getenv('FLOW_URL_RETURN');
                break;
            case Operation::Confirmation :
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_CONFIRM') : getenv('FLOW_URL_CONFIRM');
                break;
            case Operation::Failure :
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_FAILED') : getenv('FLOW_URL_FAILED');
                break;
        }
        return $url;
    }


    /*
    *   Not implemente yet
    */
    public function confirmOrder()
    {
        // $flowAPI = new flowAPI();

        // try {
        //     // Read data sent by flow
        //     $flowAPI ->read_confirm();
        // } catch (Exception $e) {
        //     // if something is wrong tell flow there is an error
        //     echo $flowAPI ->build_response(false);
        //     return;
        // }

        // //Responde with adknow
        // $flowAPI->build_response(true);
    }

    /*
    *   Get data from flow when Failed
    */
    public function getFailedOrderDetails(string $company): array
    {        
        try {
        $results = $this->getFlowResults($company, 'failed');

        return $results;
        } catch (FlowException $e) {
            throw new FlowException($e->getMessage());
        }
    }

    /*
    *   Get data from flow when Failed
    */
    public function getSuccessOrderDetails()
    {
        try {
            $results = $this->getFlowResults($company, 'success');
        } catch (FlowException $e) {
            throw new FlowException($e->getMessage() ?? 'Couldn\'t read  read results from flow.cl');
        }
    }


    /**
    *   Sets and returns config variables for a given company
    */
    private function getFlowConfig(string $company): array{
        //Load global config info
        $this->container->ConfigService->loadConfig();

        //Load business config info
        $this->container->ConfigService->loadConfig($company);

        // If environment is development
        if(getenv('FLOW_ENV') === 'development'){
            $successBaseUrl = getenv('FLOW_URL_DEV_SUCCESS');
            $failedBaseUrl = getenv('FLOW_URL_DEV_FAILED');
        }else{
            $successBaseUrl = getenv('FLOW_URL_SUCCESS');
            $failedBaseUrl = getenv('FLOW_URL_FAILED');
        }

        
        // Setup config
        $config = [
            'flow_url_exito' => "$successBaseUrl/$company",
            'flow_url_fracaso' => "$failedBaseUrl/$company",
            'flow_url_confirmacion' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_CONFIRM') : getenv('FLOW_URL_CONFIRM'),
            'flow_url_retorno' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_RETURN') : getenv('FLOW_URL_RETURN'),
            'flow_url_pago' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD'),
            'flow_keys' => "$this->certPath/$company",
            'flow_logPath' => $this->logPath,
            'flow_comercio' => getenv('EMAIL'),
            'flow_medioPago' => getenv('FLOW_MEDIUM '),
            'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE')
        ];
        return $config;
    }


    /**
    *   Handle config response for a given transaction
    */
    public function getFlowResults(string $company, string $status): array {
            
            //Get flow config
            $flowConfig = $this->getFlowConfig($company);
            
          try{
            $flowAPI = new flowAPI($flowConfig); 
            // Read data sent by flow
            $flowAPI->read_result();

            $data = [
                'payer' => $flowAPI->getPayer(),
                'flowNumber' => $flowAPI->getFlowNumber(),
                'order' => $flowAPI->getOrderNumber(),
                'amount' => $flowAPI->getAmount(),
                'concept' => $flowAPI->getConcept()
            ];

            return $data;
        }catch (Exception $e) {
            throw new FlowException($e->getMessage() ?? 'Couldn\'t read  read results from flow.cl');
        }

    }
}
