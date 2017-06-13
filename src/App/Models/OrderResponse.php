<?php

namespace App\Models;

/**
 * Order Data Model
 */
class OrderResponse extends Order
{


    protected $site;
    protected $flowNumber;
    protected $status;
    function __construct(
        string $orderId,
        float $amount,
        string $concept,
        string $payerEmail,
        string $flowNumber,
        string $site,
        string $status
    ) {
    
        $this->amount = $amount;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->concept = $concept;
        $this->payerEmail = $payerEmail;
        $this->site = $site;
        $this->flowNumber = $flowNumber;
    }
}
