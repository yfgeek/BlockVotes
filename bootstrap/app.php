<?php

use Respect\Validation\Validator as v;
/**
 * The bootstrap
 * @author  Yifan Wu
 */
session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
	$dotenv = (new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
	//
}

// Debug plugin
$app = new \Slim\App([
	'settings' => [
        'debug'         => true,
        'whoops.editor' => 'sublime',
		'displayErrorDetails' => true
	],
]);

require_once __DIR__ . '/database.php';



$container = $app->getContainer();

$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

$container['auth'] = function($container) {
	return new \App\Auth\Auth;
};

$container['flash'] = function($container) {
	return new \Slim\Flash\Messages;
};

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views/', [
		'cache' => false,
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));

	$view->getEnvironment()->addGlobal('auth',[
		'check' => $container->auth->check(),
		'user' => $container->auth->user()
	]);

    $view->getEnvironment()->addGlobal("current_path", $container["request"]->getUri()->getPath());

    $view->getEnvironment()->addGlobal('flash',$container->flash);

	return $view;
};

$container['validator'] = function ($container) {
	return new App\Validation\Validator;
};

$container['HomeController'] = function($container) {
	return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container) {
	return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container) {
	return new \App\Controllers\Auth\PasswordController($container);
};

$container['EASettingController'] = function($container) {
    return new \App\Controllers\EA\EASettingController($container);
};

$container['BallotController'] = function($container) {
    return new \App\Controllers\RA\BallotController($container);
};
$container['BallotAPIController'] = function($container) {
    return new \App\Controllers\RA\BallotAPIController($container);
};

$container['VoteController'] = function($container) {
    return new \App\Controllers\EA\VoteController($container);
};

$container['BlockChainController'] = function($container) {
    return new \App\Controllers\EA\BlockChainController($container);
};


$container['VoteAPIController'] = function($container) {
    return new \App\Controllers\EA\VoteAPIController($container);
};

$container['CandidateAPIController'] = function($container) {
    return new \App\Controllers\RA\CandidateAPIController($container);
};


$container['VoterController'] = function($container) {
    return new \App\Controllers\Voter\VoterController($container);
};

$container['PublicAPIController'] = function($container) {
    return new \App\Controllers\Voter\PublicAPIController($container);
};

$container['CandidateController'] = function($container) {
    return new \App\Controllers\RA\CandidateController($container);
};


$container['csrf'] = function($container) {
	return new \Slim\Csrf\Guard;
};

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);



v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';
