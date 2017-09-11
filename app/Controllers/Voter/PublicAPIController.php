<?php

namespace App\Controllers\Voter;
use App\Controllers\Controller;
use App\Models\Signatures;
use App\Models\VoteList;
use App\Models\Key;
use App\Models\Code;
use App\Models\User;
use Respect\Validation\Validator as v;

/**
 * Class PublicAPIController
 * @author  Yifan Wu
 * The Public API Controller for voters or anyone, ALL PUBLIC
 * @package Controllers\Voter
 */
class PublicAPIController extends Controller
{

    public function generateJson($response, $success, $message, $param = null)
    {
        $return_message["success"] = $success;
        $return_message["content"] = $message;
        $return_message["param"] = $param;
        return $response->withJson($return_message);
    }

    public function isStarted($id)
    {
        return VoteList::all()->where('id',$id)->first()->is_started== '1';
    }

    /**
     * Get unused bitcoin address
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getBitcoinAddress($request, $response){
        $id =  $request->getParam('item_id');
        $data = Key::select('id','bitcoin_address','is_paid')->where('used_for',$id)->where('is_used','0')->get();
        if($data){
            return $this->generateJson($response, '1',$data, $data->count());
        }else{
            return $this->generateJson($response, '0',["content" => 'Failed']);

        }
    }

    /**
     * Get all bitcoin address
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getAllBitcoinAddress($request, $response){
        $id =  $request->getParam('item_id');
        $data = Key::select('id','bitcoin_address','is_paid')->where('used_for',$id)->get();
        if($data){
            return $this->generateJson($response, '1',$data, $data->count());
        }else{
            return $this->generateJson($response, '0',["content" => 'Failed']);

        }
    }

    /**
     * Get EA bitcoin address
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getEABitcoinAddress($request, $response){
        $data = User::select('bitcoin_address')->where('id','2')->get();
        if($data){
            return $this->generateJson($response, '1',$data, $data->count());
        }else{
            return $this->generateJson($response, '0',["content" => 'Failed']);
        }
    }


    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function postPubKey($request, $response)
    {
        $query = $request->getAttribute('code');
        $validation = $this->validator->validate($request,[
            'public_key' => v::noWhitespace()->notEmpty(),
        ]);
        $public_key = base64_decode($request->getParam('public_key'));

        // CASE: Invalid public key
        if (!$public_key && $validation->failed())
            return $this->generateJson($response, '0',"Have you generate a correct public key? Try again!");

        // CASE: Is Started
        if($this->isStarted($query->item_id))
            return $this->generateJson($response, '0',"The voting has been started, the system can not update or save your keys.");

        // CASE: ALL CASE PASSED
        $query->update(['public_key' => $public_key]);
        return $this->generateJson($response, '1',"Your public key has been updated");

    }

    /**
     * Post the sig,hash(sig) Pair
     * @param $request
     * @param $response
     */
    public function postSigPair($request, $response){
        $sig = $request->getParam('sig');
        $hash = sha1($sig);
        $signatures = new Signatures();
        $signatures->setSigPair($sig,$hash);
        return $this->generateJson($response, '1','Your signature and sha1(sig) has been saved into our system');
    }

    /**
     * Get the sig,hash(sig) Pair
     * @param $request
     * @param $response
     */
    public function getSigPair($request, $response){
        $hash = $request->getParam('hash');
        $signatures = new Signatures();
        $query = $signatures->where('sig_hash',$hash)->first();
        if(!$query)
            return $this->generateJson($response, '0','Fetch error');
        return $this->generateJson($response, '1',$query);
    }

    /**
     * All others public keys
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getAllPubKeys($request, $response){
        $id =  $request->getParam('item_id');
        $query = new Code();

        $data = $query->select('public_key')->where([
            ['item_id', '=', $id],
            ['public_key', '<>', '0']
        ]);

        if($code =  $request->getParam('code'))
            $data = $data->where('code', '<>', $code);

        return $this->generateJson($response, '1',$data->get());

    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getBitCoinPriv($request, $response){
        $id =  $request->getParam('bitcoin_address');
        $data = Key::select('id','bitcoin_address','bitcoin_private_key','bitcoin_public_key')->where('id',$id);
        if($data->where('is_used','0')->count()>0){
            $code =  new Code();
            $query = $code->useCode($request->getParam('code'));
            if(!$query)
                 return $this->generateJson($response, '0','There is something wrong with your code');;
            $data->update(['is_used' => 1]);
            $result =  Key::select('id','bitcoin_address','bitcoin_private_key','bitcoin_public_key')->where('id',$id)->first();
            return $this->generateJson($response, '1',$result, '');
        }

        return $this->generateJson($response, '0','Your bitcoin address has been you used, please select another one');

    }


}