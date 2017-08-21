<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Signatures Model
 * @author  Yifan Wu
 * @package Model
 */
class Signatures extends Model
{

	protected $table = 'signatures';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'sig_hash',
        'sig_msg'
    ];

    public function setSigPair($sig,$hash)
    {
        $this->create([
            'sig_hash' => $hash,
            'sig_msg' => $sig
        ]);
    }
}