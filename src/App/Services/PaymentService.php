<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderResponse;
use App\Models\Operation;
use App\Exceptions\FlowException;
use \Interop\Container\ContainerInterface;
use flowAPI;

class PaymentService extends BaseService
{

    private $logPath;
    private $certPath;
    private $utilService;

    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        //Get app log path and cert path
        $this->logPath = $this->container->settings['flow']['logPath'];
        $this->certPath = $this->container->settings['flow']['certPath'];
        $this->utilService = $container->get('UtilService');
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
            case Operation::Payment:
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD');
                break;
            case Operation::Return:
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_RETURN') : getenv('FLOW_URL_RETURN');
                break;
            case Operation::Confirmation:
                $url = getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_CONFIRM') : getenv('FLOW_URL_CONFIRM');
                break;
            case Operation::Failure:
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
    *   Get order data from flow
    */
    public function getFlowOrderDetails(string $company, string $status): OrderResponse
    {
        try {
            $results = $this->getFlowResults($company, $status);
            return $results;
        } catch (FlowException $e) {
            throw new FlowException($e->getMessage());
        }
    }


    /**
    *   Sets and returns config variables for a given company
    */
    private function getFlowConfig(string $company): array
    {
        //Load global config info
        $this->container->ConfigService->loadConfig();

        //Load business config info
        $this->container->ConfigService->loadConfig($company);

        // If environment is development
        if (getenv('FLOW_ENV') === 'development') {
            $successBaseUrl = getenv('FLOW_URL_DEV_SUCCESS');
            $failedBaseUrl = getenv('FLOW_URL_DEV_FAILED');
        } else {
            $successBaseUrl = getenv('FLOW_URL_SUCCESS');
            $failedBaseUrl = getenv('FLOW_URL_FAILED');
        }

        
        // Setup config
        $config = [
            'flow_url_exito' => sprintf('%s/%s', $this->certPath, $company),
            'flow_url_fracaso' => sprintf('%s/%s', $this->certPath, $company),
            'flow_url_confirmacion' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_CONFIRM') : getenv('FLOW_URL_CONFIRM'),
            'flow_url_retorno' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_DEV_RETURN') : getenv('FLOW_URL_RETURN'),
            'flow_url_pago' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD'),
            'flow_keys' => sprintf('%s/%s', $this->certPath, $company),
            'flow_logPath' => $this->logPath,
            'flow_comercio' => getenv('EMAIL'),
            'flow_medioPago' => getenv('FLOW_MEDIUM '),
            'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE'),
        ];
        
        return $config;
    }


    /**
    *   Handle config response for a given transaction
    */
    public function getFlowResults(string $company, string $status): OrderResponse
    {
            
            //Get flow config
            $flowConfig = $this->getFlowConfig($company);
            
        try {
            $flowAPI = new flowAPI($flowConfig);
            // Read data sent by flow
            $flowAPI->read_result();

            $orderResponse = new OrderResponse(
                $flowAPI->getOrderNumber(),
                $flowAPI->getAmount(),
                $flowAPI->getConcept(),
                $flowAPI->getPayer(),
                $flowAPI->getFlowNumber(),
                getenv('SITE_URL'),
                $status
            );

            return $orderResponse;
        } catch (Exception $e) {
            throw new BadSignatureException($e->getMessage() ?? 'Couldn\'t read  read results from flow.cl');
        }
    }

    public function storeOrderData(OrderResponse $orderData): string
    {
        $token = $this->utilService->generateRandomToken();
        if ($token) {
            $_SESSION[$token] = $orderData;
            return $token;
        } else {
            throw new TokenException();
        }
    }

    public function retrieveOrderData(string $token): array
    {
        return $_SESSION[$token] ?? [];      
    }
}
