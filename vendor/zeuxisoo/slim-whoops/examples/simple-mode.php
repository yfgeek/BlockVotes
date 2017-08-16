<?php
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use Slim\App;
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

// Add the whoops middleware
$app->add(new WhoopsMiddleware($app));

// Throw exception, Named route does not exist for name: hello
$app->get('/', function($request, $response, $args) {
    return $this->router->pathFor('hello');
});

$app->run();
