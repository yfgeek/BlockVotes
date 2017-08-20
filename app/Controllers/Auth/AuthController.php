<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\Code;

use App\Models\Role;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;

/**
 * Class AuthController
 * @author  Yifan Wu
 * The Auth Controller to show the page of sign in or sign out
 * @package Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * User sing out Page
     * @param object $request
     * @param object $response
     * @return mixed
     */
	public function getSignOut($request,$response)
	{
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('home'));
	}

    /**
     * User sing in Page
     * @param $request
     * @param $response
     * @return mixed
     */
	public function getSignIn($request,$response)
	{
		return $this->view->render($response,'auth/signin.twig');
	}

    /**
     * POST: user sign in
     * @param $request
     * @param $response
     * @return mixed
     */
	public function postSignIn($request,$response)
	{
		$auth = $this->auth->attempt(
			$request->getParam('username'),
			$request->getParam('password')
		);

		if (!$auth) {
			$this->flash->addMessage('error','Please check your username or password');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		return $response->withRedirect($this->router->pathFor('auth.dashboard'));
	}

    /**
     * User sing up Page
     * @param $request
     * @param $response
     * @return mixed
     */
	public function getSignUp($request,$response)
	{
		return $this->view->render($response,'auth/signup.twig');
	}

    /**
     * Post: user sign up
     * @param $request
     * @param $response
     * @return mixed
     */
	public function postSignUp($request,$response)
	{

		$validation = $this->validator->validate($request,[
			'username' => v::noWhitespace()->notEmpty()->usernameAvailable(),
//            'code' => v::noWhitespace()->notEmpty()->codeAvailable(),
            'password' => v::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}


		$user = User::create([
			'username' => $request->getParam('username'),
			'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
            'role' => 2
		]);

//        $code = Code::useCode($user->id,$request->getParam('code'));

		$this->flash->addMessage('info','You have been signed up');

		$this->auth->attempt($user->username,$request->getParam('password'));

		return $response->withRedirect($this->router->pathFor('home'));
	}

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
	public function getDashboard($request,$response)
    {
        $role= $this->auth->user()->role;
        if($role=='1')
            return $response->withRedirect($this->router->pathFor('ra.dashboard'));

        if($role=='2')
            return  $response->withRedirect($this->router->pathFor('ea.dashboard'));

    }
}