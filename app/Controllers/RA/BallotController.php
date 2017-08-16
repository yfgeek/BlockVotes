<?php

namespace app\Controllers\RA;

use App\Controllers\Controller;

use App\Models\Code;
use App\Models\VoteList;


class BallotController extends Controller
{

    /**
     * Dashboard Page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getDashBoard($request, $response)
    {
        $data = $this->auth->user();

        return $this->view->render($response,'ra/dashboard.twig',['user'=>$data]);

    }

    /**
     * Add a voter Page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getAddVoter($request, $response){
        $result = VoteList::all();
        return $this->view->render($response,'ra/addVoter.twig',['data'=>$result]);
    }

    /**
     * List all ballot Page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getBallot($request, $response)
    {
        $data = Code::all();

        return $this->view->render($response,'ra/ballot.twig',['data'=>$data]);
    }

    /**
     * Generate a number of list
     * @param $request
     * @param $response
     * @return mixed
     */
    public function postBallot($request,$response)
    {

        $code = new Code();
        $code->generateCode($request->getParam('number'),$request->getParam('item_id'));
        return $response->withRedirect($this->router->pathFor('ra.ballot'));
    }

}