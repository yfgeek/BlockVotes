<?php

namespace App\Middleware;

/**
 * RA Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class RAMiddleware extends Middleware
{
    /**
     * To judge if the user role is RA
     * @param $request
     * @param $response
     * @param $next
     * @return next page
     */
    public function __invoke($request,$response,$next){

        if(!$this->container->auth->check()){
            $this->container->flash->addMessage('error','Please sign in before doing that');
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }else if($this->container->auth->user()->role != 1) {
            $this->container->flash->addMessage('error','This page is only show to the registration authority');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }
        $response = $next($request,$response);
        return $response;
    }

}