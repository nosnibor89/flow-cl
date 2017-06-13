<?php

namespace App\Services;

use App\Models\OrderResponse;

class UtilService extends BaseService
{
    /**
    * Get data in configuration files (.env for global data)
    * @param    $companyName    string      Company name, if null it takes the .env global file
    */
    public function assembleUrl(OrderResponse $order): string
    {
        $siteToRedirect = $order->site;
        // unset($data['site']);
        $queryParams = http_build_query($order);
        return "$siteToRedirect?$queryParams";
    }
}
