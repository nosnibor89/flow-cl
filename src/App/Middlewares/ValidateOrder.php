<?php
namespace App\Middlewares;

use App\Models\Order;
use \Interop\Container\ContainerInterface;

class ValidateOrder extends BaseMiddleware
{
    private $hasError;
    private $error;

    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
    
    /**
     * Validation middleware invokable class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $this->errors = [];
        $this->hasErrors = false;
        $parsedBody = $request->getParsedBody(); // Get Data from request

        //Validate Company
        if (!$this->isApiKeyValid($parsedBody)) {
            $this->hasErrors = true;
            $error = [
                'code' => 1,
                'message' => 'Incorrect company or not found'
            ];

            array_push($this->errors, $error);
        }

        //Validate order data
        if (!$this->isOrderDataValid($parsedBody)) {
            $this->hasErrors = true;
            $error = [
                'code' => 2,
                'message' => 'Order params are incorrect'
            ];

            array_push($this->errors, $error);
        }

        $request = $request->withAttribute('hasErrors', $this->hasErrors);
        $request = $request->withAttribute('errors', $this->errors);

        return $next($request, $response);
    }

    /*
    *   Validate New Order Data
    */
    private function isOrderDataValid(array $parsedBody): bool
    {
        if (empty($parsedBody['order']) || empty($parsedBody['amount']) || empty($parsedBody['concept']) || empty($parsedBody['payer'])) {
            return false;
        }

        if (!is_numeric($parsedBody['amount'])) {
            return false;
        }

        if (!$this->isEmailValid($parsedBody['payer'])) {
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

    /*
    *   Validate APIKEY company
    */
    public function isApiKeyValid(array $parsedBody): bool
    {
        $this->configService->loadConfig($parsedBody['company']);

        $apikey = getenv('APIKEY') ? getenv('APIKEY') : getenv('APIKEY');

        return ($apikey && $apikey === $parsedBody['apikey'] ) ? true: false;
    }
}
