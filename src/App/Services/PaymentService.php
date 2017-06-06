<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Operation;
use \Interop\Container\ContainerInterface;
use flowAPI;

class PaymentService extends BaseService
{

    private $logPath;
    private $certPath;

    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        //Get app log path and cert path
        $this->logPath = $this->container->settings['flow']['logPath'];
        $this->certPath = $this->container->settings['flow']['certPath'];
    }

    /**
    *   Creates a payment order
    */
    public function createOrder(string $company, Order $order)
    {
        //Load global config info
        $this->container->ConfigService->loadConfig();

        //Load business config info
        $businessConfig = $this->container->ConfigService->loadConfig($company);

        // Setup config
        $flowConfig = [
            'flow_url_exito' => getenv('FLOW_URL_SUCCESS'),
            'flow_url_fracaso' => getenv('FLOW_URL_FAILED'),
            'flow_url_confirmacion' => getenv('FLOW_URL_CONFIRM'),
            'flow_url_retorno' => getenv('FLOW_URL_RETURN'),
            'flow_url_pago' => getenv('FLOW_ENV') === 'development' ? getenv('FLOW_URL_TEST') : getenv('FLOW_URL_PROD'),
            'flow_keys' => "$this->certPath/$company",
            'flow_logPath' => $this->logPath,
            'flow_comercio' => getenv('EMAIL'),
            'flow_medioPago' => getenv('FLOW_MEDIUM '),
            'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE')
        ];

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
                $url = getenv('FLOW_URL_RETURN');
                break;
            case Operation::Confirmation :
                $url = getenv('FLOW_URL_CONFIRM');
                break;
            case Operation::Failure :
                $url = getenv('FLOW_URL_FAILED');
                break;
        }
        return $url;
    }

    public function confirmOrder()
    {
        $flowAPI = new flowAPI();

        try {
            // Read data sent by flow
            $flowAPI ->read_confirm();
        } catch (Exception $e) {
            // if something is wrong tell flow there is an error
            echo $flowAPI ->build_response(false);
            return;
        }

        //Responde with adknow
        $flowAPI->build_response(true);
    }

    public function handleFailedOrder()
    {
        try {
            // Read data sent by flow
            $flowAPI->read_result();
            // TODO: Find a way to let the user know something bad happened
        } catch (Exception $e) {
            error_log($e->getMessage());
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Ha ocurrido un error interno', true, 500);
            return;
        }
    }

    public function handleSuccessOrder()
    {
        try {
                // Read data sent by flow
                $flowAPI->read_result();
                // TODO: Find a way to let the user the payment was success
        } catch (Exception $e) {
            error_log($e->getMessage());
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Ha ocurrido un error interno', true, 500);
            return;
        }
    }
}
