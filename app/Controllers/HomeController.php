<?php

namespace App\Controllers;

use App\Models\User;
/**
 * Home Controller
 * @author  Yifan Wu
 * The HomeController
 * @package Controllers
 */
class HomeController extends Controller
{
	public function index($request,$response)
	{
	    if($this->auth->check()){
            return $response->withRedirect($this->router->pathFor('auth.dashboard'));
        }else{
            return $this->view->render($response,'home.twig');
        }

	}
}