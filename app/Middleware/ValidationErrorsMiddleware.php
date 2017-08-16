<?php

namespace App\Middleware;

/**
* 
*/
class ValidationErrorsMiddleware extends Middleware
{
	
	public function __invoke($request,$response,$next)
	{
		// var_dump(isset($_SESSION['errors']));die();
		$this->container->view->getEnvironment()->addGlobal('errors',isset($_SESSION['errors']) ? $_SESSION['errors'] : '');
		unset($_SESSION['errors']);

		$response = $next($request,$response);
		return $response;
		
	}
}