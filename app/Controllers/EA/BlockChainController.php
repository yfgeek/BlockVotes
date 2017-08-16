<?php

namespace app\Controllers\EA;

use App\Controllers\Controller;
use App\Models\VoteList;

class BlockChainController extends Controller
{

    /**
     * VoteList page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getBalanceList($request, $response)
    {
        $result = VoteList::all();
        return $this->view->render($response,'ea/balance.twig',['data'=>$result]);
    }

    /**
     * Add a Vote Page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getFeeList($request, $response)
    {
        $result = VoteList::all();
        $user = $this->auth->user();
        return $this->view->render($response,'ea/fee.twig',['data'=>$result,'user'=>$user]);
    }





}