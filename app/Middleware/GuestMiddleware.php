<?php

namespace App\Middleware;

/**
 * Guest Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class GuestMiddleware extends Middleware
{
    /**
     * A public page
     * @param $request
     * @param $response
     * @param $next
     * @return next page
     */
	public function __invoke($request,$response,$next)
	{
		if($this->container->auth->check()) {
			return $response->withRedirect($this->container->router->pathFor('home'));
		}

		$response = $next($request,$response);
		return $response;
		
	}
}