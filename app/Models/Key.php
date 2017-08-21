<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

use BitcoinPHP\BitcoinECDSA\BitcoinECDSA;

use AsymmetriCrypt\Crypter;
/**
 * Key Model
 * @author  Yifan Wu
 * @package Model
 */
class Key extends Model
{
    protected $table = 'key';
    public $timestamps = false;

    protected $fillable = [
        'bitcoin_public_key',
        'bitcoin_private_key',
        'bitcoin_address',
        'is_used',
        'used_for'
    ];

    public function generateBitcoin($numbers,$item_id){
        $bitcoinECDSA = new BitcoinECDSA();
        $bitcoinECDSA->setNetworkPrefix('6f'); //testnet
        for($i=0;$i<$numbers;$i++){
            $bitcoinECDSA->generateRandomPrivateKey();
//            $priv = Crypter::createPrivateKey();
//            $pub = $priv->getPublicKey();

            $this->create([
                'bitcoin_private_key' =>  $bitcoinECDSA-> getWif(),
                'bitcoin_public_key' => $bitcoinECDSA->getPubKey(),
                'bitcoin_address' => $bitcoinECDSA->getAddress(),
                'is_used' => '0',
                'used_for' => $item_id
            ]);
        }
    }
}