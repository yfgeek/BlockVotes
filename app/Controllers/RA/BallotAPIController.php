<?php

namespace App\Controllers\RA;

use App\Controllers\Controller;
use App\Models\Code;
use App\Models\VoteList;
use App\Models\Key;
use App\Models\Candidate;

/**
 * Class BallotAPIController
 * @author  Yifan Wu
 * The Ballot API Controller to send an email to the voter
 * @package Controllers\RA
 */
class BallotAPIController extends Controller
{

    /**
     * API
     * Add a new voter
     *
     * @param $request
     * @param $response
     * @return JSON {"success": 0/1,"content": "Error code"}
     *
     */
    public function postAddVoter($request, $response){
        $data = array('success' => '0');
        $email = $request->getParam('email');
        $firstname = $request->getParam('firstname');
        $vote_id = $request->getParam("vote_id");

        $vote_item = $this->getVoteTitle($vote_id);
        $code =  new Code();
        $codeid =$code->generateCode(1,$vote_id);

        $content= "Hi, " . $firstname .  "<br /><br />You are ready to vote for <strong>" .$vote_item . "</strong>  <br />Please follow this URL to sign up as a voter to vote. <br /><br />  http://localhost/vote/home?code=" . $codeid . "<br /><br />   Best,<br /><br />   The Blockvotes Team" ;

        $transport = (new \Swift_SmtpTransport( getenv('STMP_SERVER'), getenv('STMP_PORT'),'ssl'))
            ->setUsername(getenv('STMP_USERNAME'))
            ->setPassword(getenv('STMP_PASSWORD'))
        ;
        $mailer = new \Swift_Mailer($transport);

        $message =(new \Swift_Message('Start your vote now'))
            ->setFrom(array(getenv('STMP_USERNAME') => 'BlockVotes Team'))
            ->setTo(array($email => 'Voter'))
            ->setBody($content)
            ->setContentType("text/html");

        if($mailer->send($message)){
            $data['success'] = 1;
        };

        $key = new Key();
        $key->generateBitcoin(1,$vote_id);

        return  $response->withJson($data);
    }

    /**
     * Helper function
     * @param $id
     * @return mixed
     */
    public function getVoteTitle($id){
        $result = VoteList::where('id',$id)->first();
        return $result->title;
    }


}