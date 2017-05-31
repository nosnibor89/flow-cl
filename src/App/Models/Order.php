<?php

namespace App\Models;

/**
 * Order Data Model
 */
class Order
{

    public $orderId;
    public $amount;
    public $concept;
    public $payerEmail;


    function __construct(string $orderId, float $amount, string $concept, string $payerEmail)
    {
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->concept = $concept;
        $this->payerEmail = $payerEmail;
    }
}
