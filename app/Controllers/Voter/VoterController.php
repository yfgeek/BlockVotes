<?php

namespace app\Controllers\Voter;

use App\Controllers\Controller;
use App\Models\User;

use App\Models\VoteList;

/**
 * Class VoterController
 * @author  Yifan Wu
 * The Voter Controller for voters to vote
 * @package Controllers\Voter
 */
class VoterController extends Controller
{
    /**
     *
     * @param $query
     * @return mixed
     */
    public function getData($query)
    {
            $data["title"] ="";
            if ($query) {
                $vote_list = VoteList::all()->where('id', $query->item_id)->first();
                $data["title"] = $vote_list->title;
                $data["item_id"] = $vote_list->id;
                $data["code"] = $query->code;
                $data["public_key"] = $query->public_key;
                $data["is_started"] =$vote_list->is_started;
                $data["description"] =$vote_list->description;
            }
            return $data;

    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getVote($request, $response)
    {
        return $this->view->render($response,'voter/home.twig',$this->getData($request->getAttribute('code')));
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getStarted($request, $response){
        $data = $this->getData($request->getAttribute('code'));

        if($data["is_started"] == '0'){
            if($data["public_key"]!='0'){
                return $response->withRedirect($this->router->pathFor('vote.wait').'?code='.$data["code"]);
            }else{
                return $response->withRedirect($this->router->pathFor('vote.fill').'?code='.$data["code"]);
            }
        }else{
            if($data["public_key"]!='0'){
                return $response->withRedirect($this->router->pathFor('vote.vote').'?code='.$data["code"]);
            }else{
                return $response->withRedirect($this->router->pathFor('vote.lost'));
            }
        }
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getFailed($request, $response){
        return $this->view->render($response,'voter/fail.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getFillForm($request, $response)
    {
        $data = $this->getData($request->getAttribute('code'));
        return $this->view->render($response,'voter/fill.twig',$data);

    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getWait($request, $response)
    {
        $data = $this->getData($request->getAttribute('code'));
        if($data["public_key"]=='0') return $response->withRedirect($this->router->pathFor('vote.fill').'?code='.$data["code"]);

        return $this->view->render($response,'voter/wait.twig',$data);
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getCandidate($request, $response)
    {
        $data = $this->getData($request->getAttribute('code'));
        return $this->view->render($response,'voter/vote.twig',$data);

    }

    public function getTally($request, $response){
        $vote_item = (int) $request->getParam('vote_item');
        $eaddress = User::select('bitcoin_address')->where('id','2')->first();
        if(!$vote_item)
            return $response->withRedirect($this->router->pathFor('tally.home'));
        $result = VoteList::all()->where('id',$vote_item)->first();
        if(!$result)
            return $response->withRedirect($this->router->pathFor('tally.home'));

        return $this->view->render($response,'tally/tally.twig',['item_id'=>$vote_item,'title'=>$result->title,'eaaddress' => $eaddress->bitcoin_address]);
    }

    public function getTallyHome($request, $response){
        $vote_list = VoteList::all();
        return $this->view->render($response,'tally/home.twig',['data'=>$vote_list]);
    }

    public function getVerify($request, $response){
        $vote_item = (int) $request->getParam('vote_item');
        $eaddress = User::select('bitcoin_address')->where('id','2')->first();
        if(!$vote_item)
            return $response->withRedirect($this->router->pathFor('verify.home'));
        $result = VoteList::all()->where('id',$vote_item)->first();
        if(!$result)
            return $response->withRedirect($this->router->pathFor('verify.home'));

        return $this->view->render($response,'verify/verify.twig',['item_id'=>$vote_item,'title'=>$result->title,'eaaddress' => $eaddress->bitcoin_address]);
    }

    public function getVerifyHome($request, $response){
        $vote_list = VoteList::all();
        return $this->view->render($response,'verify/home.twig',['data'=>$vote_list]);
    }
//    public function getVoteList($request, $response)
//    {
//        $data = UserVote::all()->where('user_id',$this->auth->user()->id)->first();
//        $result = VoteList::all()->where('id',$data['id']);
//
//        return $this->view->render($response,'voter/vote.twig',['data'=>$result]);
//    }
//
//    public function postVote($request,$response)
//    {
//
//        $validation = $this->validator->validate($request,[
//            'code' => v::noWhitespace()->notEmpty()->codeAvailable()
//        ]);
//
//        if ($validation->failed()) {
//            return $response->withRedirect($this->router->pathFor('voter.vote'));
//        }
//        $code = Code::useCode($request->getParam('code'));
//
//        UserVote::create([
//            'user_id' => $this->auth->user()->id,
//            'vote_id' => $code->item_id
//        ]);
//
//        return $response->withRedirect($this->router->pathFor('voter.vote'));
//    }

}