<?php

namespace App\Services;

use App\Models\OrderResponse;

class UtilService extends BaseService
{
    /**
    * Get data in configuration files (.env for global data)
    * @param    $companyName    string      Company name, if null it takes the .env global file
    */
    public function assembleUrl(OrderResponse $order, string $token): string
    {
        $siteToRedirect = $order->site;
        $orderStatus = $order->status;
        $queryParams = http_build_query(['token' => $token, 'status' => $orderStatus]);
        return "$siteToRedirect?$queryParams";
    }

    public function generateRandomToken(int $length = 32) : string
    {
        if (!isset($length) || intval($length) <= 8) {
              $length = 32;
        }
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }
}
