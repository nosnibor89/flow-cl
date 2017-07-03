<?php

namespace App\Services;
// use \Psr\Http\Message\ServerRequestInterface;
// \Psr\Http\Message\ResponseInterface;

use App\Models\Order;

class ValidationService extends BaseService
{

        /**
     * Validation middleware invokable class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    // public function __invoke($request, $response, $next)
    // {
    //     die('mid');
    //     $this->errors = [];
    //     $params = $request->getParams();
    //     $params = array_merge((array) $request->getAttribute('routeInfo')[2], $params);
    //     $this->validate($params, $this->validators);
    //     $request = $request->withAttribute($this->errors_name, $this->getErrors());
    //     $request = $request->withAttribute($this->has_errors_name, $this->hasErrors());
    //     $request = $request->withAttribute($this->validators_name, $this->getValidators());
    //     $request = $request->withAttribute($this->translator_name, $this->getTranslator());
    //     return $next($request, $response);
    // }


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
