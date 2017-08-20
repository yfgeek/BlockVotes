<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

/**
 * Class PasswordController
 * @author  Yifan Wu
 * The Password Controller to show the page of sign in or sign out
 * @package Controllers\Auth
 */
class PasswordController extends Controller
{
    /**
     * Change the password Page
     * @param $request
     * @param $response
     * @return mixed
     */
	public function getChangePassword($request,$response)
	{
		return $this->view->render($response,'auth/password/change.twig');
	}

    /**
     * POST: change the password
     * @param $request
     * @param $response
     * @return mixed
     */
	public function postChangePassword($request,$response)
	{
		$validation = $this->validator->validate($request,[
			'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));

		$this->flash->addMessage('info','Your password was changed');

		return $response->withRedirect($this->router->pathFor('home'));

	}
}