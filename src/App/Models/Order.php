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
    public $medium;


    function __construct(string $orderId, float $amount, string $concept, string $payerEmail, string $medium)
    {
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->concept = $concept;
        $this->payerEmail = $payerEmail;
        $this->medium = $medium;
    }
}
