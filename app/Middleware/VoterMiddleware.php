<?php

namespace App\Middleware;

/**
 * Voter Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class VoterMiddleware extends Middleware
{
    /**
     * Validate the code and transfer it to the next page
     * @param $request $code
     * @param $response
     * @param $next
     * @return next page with $code
     */
    public function __invoke($request,$response,$next)
    {
        $query = $this->container->auth->checkCode($request->getParam('code'));
        if(! $query) {
            return $response->withRedirect($this->container->router->pathFor('vote.fail'));
        }
        $request = $request->withAttribute('code', $query);
        $response = $next($request,$response);
        return $response;

    }

}