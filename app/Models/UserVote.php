<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * UserVote Model
 * @author  Yifan Wu
 * @package Model
 */
class UserVote extends Model
{

	protected $table = 'vote_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'vote_id'
    ];


}