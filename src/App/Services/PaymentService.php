<?php

namespace App\Services;

require(__DIR__."/../../flowAPI.php");


class PaymentService extends BaseService
{
    // Dummy
	public function getConfig($companyName) {
		//Get business config
		// $accountConfig = $this->container->ConfigService->getConfig($companyName);
		//Get global app config
        $flowAPI = new \flowAPI();
        print_r($flowAPI);
		$globalConfig = $this->container->ConfigService->getConfig();
		return $globalConfig;
	}
	
	public function createOrder($company,$orderData){
		$date = new DateTime();
		$timestamp = $date->format('YmdHis');
		
		//Get custom business info
		list($orderId, $amount, $concept, $payerEmail, $gecos, $home, $shell) = explode(":", $orderData);
		
		//Get global info
		// $medioPago = $flow_medioPago;
		// $orderId = $_POST['orden'];
		// $monto = $_POST['monto'];
		// $concepto = $_POST['concepto'];
		// $email_pagador = $_POST['pagador'];
        $globalConfig = $this->container->ConfigService->getConfig();

		$paymentMedium = $globalConfig['FLOW_MEDIUM'];
		
        

		//Make request
        $flowAPI = new flowAPI();
		        try {
			$flow_pack = $flowAPI->new_order($orden_compra, $monto, $concepto, $email_pagador);
			// Si desea enviar el medio de pago usar la siguiente línea
				//$flow_pack = $flowAPI->new_order($orden_compra, $monto, $concepto, $email_pagador, $medioPago);
			
		}
		catch (Exception $e) {
			header('location: error.php');
		}
		
	}
}
