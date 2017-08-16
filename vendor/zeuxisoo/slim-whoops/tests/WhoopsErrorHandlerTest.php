<?php
use Slim\Http\Response;

use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler;

use Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler;

class WhoopsErrorHandlerTest extends PHPUnit_Framework_TestCase {

    public function testInvoke() {
        $whoops = new WhoopsRun;
        $whoops->pushHandler(new PrettyPageHandler());

        $request   = $this->getMockBuilder('Slim\Http\Request')->disableOriginalConstructor()->getMock();
        $response  = new Response();

        $whoopsErrorHandler  = new WhoopsErrorHandler($whoops);
        $whoopsErrorResponse = $whoopsErrorHandler($request, $response, new \Exception());

        $this->assertEquals(500, $whoopsErrorResponse->getStatusCode());
    }

}
