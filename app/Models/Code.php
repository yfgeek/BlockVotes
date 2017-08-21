<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Code Model
 * @author  Yifan Wu
 * @package Model
 */
class Code extends Model
{
    protected $table = 'code';
    protected $fillable = [
        'code',
        'is_used',
        'public_key',
        'item_id',
        'user_id'
    ];

    /**
     * @return string
     */
    public function makeCard() {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 16; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
    }

    /**
     * @param $number
     * @param $item
     * @return bool|string
     */
    public function generateCode($number,$item)
    {
        $number = intval($number);
        if($number>0 && $number<1000){
            for($i=0;$i<$number;$i++){
                $content = $this->makeCard();
                $this->create([
                    'code' => $content,
                    'used' => '0',
                    'item_id' => $item,
                    'user_id' => ''
                ]);
            }
            return $content;
        }
        return false;
    }

    /**
     * @param $code
     * @return bool
     */
    public function useCode($code){
        $query= $this->where('code',$code)->update([
            'is_used' => 1
        ]);
        if ($query) return true;

        return false;
//        $code = Code::where('code',$code)->first();
//        if($code && ($code->used == '0')){
//            $code->update([
//                'used' => '1'
//            ]);
//            return $code;
//        }
//        return false;
    }

    /**
     * @param $code
     * @return bool
     */
    public function validateCode($code){
        $query = $this->where('code', $code)->first();
        if($query && $query->is_used=='0')
                return $query;
        return false;
    }

    /**
     * @param $pub_key
     */
    public function setPubKey($pub_key)
    {
        $this->update([
            'public_key' => $pub_key
        ]);
    }


}