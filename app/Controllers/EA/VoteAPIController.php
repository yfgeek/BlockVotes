<?php
namespace App\Controllers\EA;
use App\Controllers\Controller;

use App\Models\Key;
use App\Models\VoteList;

/**
 * Class VoteAPIController
 * @author  Yifan Wu
 * @api for ea
 * The Vote API Controller to start/stop a vote item or set the profile of a vote item
 * @package Controllers\EA
 */
class VoteAPIController extends Controller
{
    /**
     * A helper method to generate Json with the standard of following:
     * {
     *  "success" : "1",
     *  "content" : {
     *               "item1" : "xxx",
     *               "item2" : "xxx",
     *               "..."   : "..."
     *              }
     *  "param"   : ""
     * }
     *
     * @param object $response transfer from the upper method to generate the Json by using withJson method
     * @param string $success status of the result, 1: succeed , 0: failed
     * @param object $message  can transfer an array or a string when javascript uses forEach to traversal
     * @param null $param a additional value if needs
     * @return as above
     */
    public function generateJson($response, $success, $message, $param = null)
    {
        $return_message["success"] = $success;
        $return_message["content"] = $message;
        $return_message["param"] = $param;
        return $response->withJson($return_message);
    }

    /**
     * API: toggle the voting
     * Goal: If the item of the voting is started,
     *       then stop it by marking the is_started as 0,
     *       Else start the item of the voting by marking the is_started as 1.
     *
     * ($vote + 1) % 2 is a great mathematics way to achieve this goal which is smarter and more efficiency
     * compared to the way of using if sentences.
     *
     * @param object $request with a parameter from the browser with vote_id
     * @param object $response transfer it to the backend to generate the real pages for user
     * @return object a json format by using withJson() method
     */
    public function getToggleVoting($request, $response){
        $id =  (int) $request->getParam('vote_id');
        $vote =  VoteList::where('id',$id)->first()->is_started;
        VoteList::where('id',$id)->update([
            'is_started' => ($vote + 1) % 2
        ]);
        return $this->generateJson($response,'1',$vote,'');
    }

    /**
     * API: get all keys for vote id
     *
     * @param object $request a parameter from the browser with id, used_for
     * @param object $response transfer it to the backend to generate the real pages for user
     * @return object a json format by using withJson() method
     */
    public function getKeysList($request, $response){
        $id =  (int) $request->getParam('id');
        $data = Key::where('used_for',$id)->get();
        return $response->withJson($data);
    }


    /**
     * @param $request
     * @param $response
     * @return as
     */
    public function getProfile($request, $response){
        $data = VoteList::where('id',(int) $request->getParam('id'))->first();
        return $this->generateJson($response,'1',$data,'');

    }

    /**
     * @param $request
     * @param $response
     * @return as
     */
    public function postProfile($request, $response){

        $query = VoteList::where('id',(int) $request->getParam('id'))->update([
            'title' => $request->getParam('title'),
            'number' => (int) $request->getParam('number'),
            'description' => $request->getParam('description')
        ]);
        if(!$query)
            return $this->generateJson($response,'0','error','');

        return $this->generateJson($response,'1','This vote has been updated','');

    }


}