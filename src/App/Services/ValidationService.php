<?php

namespace App\Services;

use App\Models\Order;

class ValidationService extends BaseService
{
    public function isOrderValid(string $companyName, string $key, Order $order): bool
    {
        $valid = false;
        $valid = isApiKeyValid($companyName, $key);
        $valid = isOrderDataValid($order);

        return $valid;
    }

    /*
    *   Validate APIKEY company
    */
    public function isApiKeyValid(string $companyName, string $key): bool
    {
        $this->container->ConfigService->loadConfig($companyName);

        $apikey = getenv('APIKEY') ? getenv('APIKEY') : getenv('APIKEY');

        return ($apikey && $apikey === $key ) ? true: false;
    }

    /*
    *   Validate New Order Data
    */
    public function isOrderDataValid(Order $order): bool
    {
        if (empty($order->orderId) || empty($order->amount) || empty($order->concept) || empty($order->payerEmail)) {
            return false;
        }

        if (!is_numeric($order->amount)) {
            return false;
        }

        if (!$this->isEmailValid($order->payerEmail)) {
            return false;
        }

        return true;
    }

    /**
    *   Validate email format
    */
    private function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true: false;
    }
}
