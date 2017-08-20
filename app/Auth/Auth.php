<?php
namespace App\Auth;

use App\Models\User;
use App\Models\Code;

/**
 * Class Auth
 * @author  Yifan Wu
 * The container includes this when EA or RA login or try to login or logout
 * @package App\Auth
 */
class Auth
{
    /**
     * @return object or string of the user's information if he is sign in, else return 0
     */
	public function user()
	{
		return User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);
	}

    /**
     * @return bool if the user is sign in
     */
	public function check()
	{
		return isset($_SESSION['user']);
	}

    /**
     *
     * @param string $code the ballot code
     * @return Code object if the code is validated else return false
     */
	public function checkCode($code){
        $query = new Code();
        return $query->validateCode($code);
    }

    /**
     * Attempt log in
     * @param $username
     * @param $password
     * @return bool
     */
	public function attempt($username, $password)
	{
		$user = User::where('username',$username)->first();

		if (!$user) {
			return false;
		}

		if (password_verify($password,$user->password)) {
			$_SESSION['user'] = $user->id;
            return true;
		}

		return false;
	}

    /**
     * log out
     */
	public function logout()
	{
		unset($_SESSION['user']);
    }
}