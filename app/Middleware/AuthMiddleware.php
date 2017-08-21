<?php

namespace App\Middleware;
/**
 * Auth Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class AuthMiddleware extends Middleware
{
    /**
     * To judge if the user is log in
     * @param $request
     * @param $response
     * @param $next
     * @return next page
     */
	public function __invoke($request,$response,$next)
	{
		if(!$this->container->auth->check()) {
			$this->container->flash->addMessage('error','Please sign in before doing that');
			return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		}

		$response = $next($request,$response);
		return $response;

	}
}