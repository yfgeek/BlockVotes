<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * User Model
 * @author  Yifan Wu
 * @package Model
 */
class User extends Model
{

	protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
        'priv_key',
        'bitcoin_address'
    ];



    public function setUsername($username)
    {
        $this->update([
            'username' => $username
        ]);
    }


    public function getBitcoinAddress()
    {
        return $this->bitcoin_address;
    }

    public function setBitcoinAddress($bitcoin_address)
    {
        $this->update([
            'bitcoin_address' => $bitcoin_address
        ]);
    }


    public function setPrivKey($key)
    {
        $this->update([
            'priv_key' => $key
        ]);
    }

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password,PASSWORD_DEFAULT)
        ]);
    }


}