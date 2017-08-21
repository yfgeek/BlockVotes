<?php

namespace App\Middleware;
/**
 * CSRF Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class CsrfViewMiddleware extends Middleware
{
    /**
     * To prevent CSRF attack
     * @param $request
     * @param $response
     * @param $next
     * @return next page
     */
	public function __invoke($request,$response,$next)
	{
		$this->container->view->getEnvironment()->addGlobal('csrf',[
			'field' => '
				<input type="hidden" name="'. $this->container->csrf->getTokenNameKey() .'"
				 value="'. $this->container->csrf->getTokenName() .'">
				<input type="hidden" name="'. $this->container->csrf->getTokenValueKey() .'"
				 value="'. $this->container->csrf->getTokenValue() .'">
			',
		]);

		$response = $next($request,$response);
		return $response;

	}
}