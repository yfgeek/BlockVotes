<?php
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use Slim\App;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsGuard;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

$app = new App([
    'settings' => [
        // On/Off whoops error
        'debug'               => true,

        // Set default whoops editor
        'whoops.editor'       => 'sublime',

        // Display call stack in orignal slim error when debug is off
        'displayErrorDetails' => true,
    ]
]);

// Get the application container
$container = $app->getContainer();

// Replace the default phpErroHandler and erroHandler without enter/call to middleware
$whoopsGuard = new WhoopsGuard();
$whoopsGuard->setApp($app);
$whoopsGuard->setRequest($container['request']);
$whoopsGuard->setHandlers([]);
$whoopsGuard->install();

// Throw exception in middleware (If it is not global mode, it will return slim default error page)
$app->add(function($request, $response, $next) {
    throw new Exception("Custom exception");

    return $next($request, $response);
});

// Throw exception in runtime (If can work in middleware / global mode)
$app->get('/', function($request, $response, $args) {
    return $this->router->pathFor('hello');
});

$app->run();
