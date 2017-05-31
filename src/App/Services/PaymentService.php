<?php

namespace App\Services;

require(__DIR__."/../../flowAPI.php");

use \flowAPI;

class PaymentService extends BaseService
{
    /**
    *   Creates a payment order
    */
    public function createOrder($company, $orderData)
    {

        //Get order info info
        extract($orderData);//$orderId, $amount, $concept, $payerEmail

        //Get app log path and cert path
        $logPath = $this->container->settings['flow']['logPath'];
        $certPath = $this->container->settings['flow']['certPath'];
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
            'flow_keys' => "$certPath/$company",
            'flow_logPath' => $logPath,
            'flow_comercio' => getenv('EMAIL'),
            'flow_medioPago' => getenv('FLOW_MEDIUM '),
            'flow_tipo_integracion' => getenv('FLOW_INTEGRATION_TYPE')
        ];

        //Make request
        $flowAPI = new flowAPI($flowConfig);
        try {
            $flow_pack = $flowAPI->new_order($orderId, $amount, $concept, $payerEmail);
            // We might need to allow the user select the payment method in the future.
            // $flow_pack = $flowAPI->new_order($orderId, $amount, $concept, $payerEmail, $medioPago);
            
            return $flow_pack;
        } catch (Exception $e) {
            throw new MyException('Failed creating flow order '. $e->getMessage());
        }
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
