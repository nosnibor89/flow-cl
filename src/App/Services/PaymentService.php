<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderResponse;
use App\Models\PaymentMedium;
use App\Models\Operation;
use App\Exceptions\FlowException;
use Predis\Connection\ConnectionException;
use \Interop\Container\ContainerInterface;
use flowAPI;

class PaymentService extends BaseService
{
    use \App\Utils\Tokenizer;
    use \App\Utils\UrlGenerator;

    private $logPath;
    private $certPath;
    private $predis;

    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        //Get app log path and cert path
        $this->logPath = $this->container->settings['flow']['logPath'];
        $this->certPath = $this->container->settings['flow']['certPath'];
        $this->predis = $this->container->predis;
    }

    /**
    *   Creates a payment order
    */
    public function createOrder(string $company, Order $order)
    {
        $flowConfig = $this->getFlowConfig($company, $order->medium);

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
                $url = getenv('FLOW_URL');
                break;
            case Operation::Return:
                $url = getenv('FLOW_URL_RETURN') ;
                break;
            case Operation::Confirmation:
                $url = getenv('FLOW_URL_CONFIRM');
                break;
            case Operation::Failure:
                $url = getenv('FLOW_URL_FAILED') ;
                break;
        }
        return $url;
    }

    /*
    *   Get order data from flow
    */
    public function getFlowOrderDetails(string $company, string $medium, string $status): OrderResponse
    {
        try {
            $results = $this->getFlowResults($company, $medium, $status);
            return $results;
        } catch (FlowException $e) {
            throw new FlowException($e->getMessage());
        }
    }

    /**
    *   Sets and returns config variables for a given company
    */
    private function getFlowConfig(string $company, string $medium): array
    {
        //Load business config info
        $this->container->ConfigService->loadConfig($company);

        $successBaseUrl = getenv('FLOW_URL_SUCCESS');
        $failedBaseUrl = getenv('FLOW_URL_FAILED');
        $confirmBaseUrl = getenv('FLOW_URL_CONFIRM');
        $returnBaseUrl = getenv('FLOW_URL_RETURN');

        // Setup config
        $config = [
            'flow_url_exito' => sprintf('%s/%s/%s', $successBaseUrl, $company, $medium),
            'flow_url_fracaso' => sprintf('%s/%s/%s', $failedBaseUrl, $company, $medium),
            'flow_url_confirmacion' => sprintf('%s/%s/%s', $confirmBaseUrl, $company, $medium),
            'flow_url_retorno' => sprintf('%s/%s/%s', $returnBaseUrl, $company, $medium),
            'flow_url_pago' => getenv('FLOW_URL'),
            'flow_keys' => sprintf('%s/%s', $this->certPath, $company),
            'flow_logPath' => $this->logPath,
            'flow_comercio' => getenv('EMAIL'),
            'flow_medioPago' => $this->getPaymentMedium($medium),
            'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE'),
        ];
        
        return $config;
    }

    private function getPaymentMedium(string $medium): int
    {
        switch (strtolower($medium)) {
            case 'webpay':
                return PaymentMedium::Webpay;
                break;
            case 'servipag':
                return PaymentMedium::Servipag;
                break;
            case 'multicaja':
                return PaymentMedium::Multicaja;
                break;
            default:
                return PaymentMedium::Webpay;
                break;
        }
    }


    /**
    *   Handle config response for a given transaction
    */
    private function getFlowResults(string $company, string $medium, string $status): OrderResponse
    {
            $flowConfig = $this->getFlowConfig($company, $medium);
                  
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
        $token = $this->generateRandomToken();
        if ($token) {
            $this->predis->set($token, json_encode($orderData));
            return $token;
        } else {
            throw new TokenException();
        }
    }

    public function retrieveOrderData(string $token): ?array
    {
        try {
            $data = json_decode($this->predis->get($token), true);
            return $data;
        } catch (ConnectionException $e) {
            throw new RuntimeException(sprintf('Could not connect to predis \n %s', $e->getMessage()));
        }
    }
}
