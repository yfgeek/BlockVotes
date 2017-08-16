<?php
namespace Zeuxisoo\Whoops\Provider\Slim;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Whoops\Run as WhoopsRun;

class WhoopsErrorHandler {

    private $whoops;

    public function __construct(WhoopsRun $whoops) {
        $this->whoops = $whoops;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $throwable) {
        $handler = WhoopsRun::EXCEPTION_HANDLER;

        ob_start();

        $this->whoops->$handler($throwable);

        $content = ob_get_clean();
        $code    = $throwable instanceof HttpException ? $throwable->getStatusCode() : 500;

        return $response
                ->withStatus($code)
                ->withHeader('Content-type', 'text/html')
                ->write($content);
    }

}
