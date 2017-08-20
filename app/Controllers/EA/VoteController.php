<?php

namespace app\Controllers\EA;

use App\Controllers\Controller;

use App\Models\VoteList;
use App\Models\Key;
use App\Models\Code;

use Respect\Validation\Validator as v;

/**
 * Class VoteController
 * @author  Yifan Wu
 * The Vote Controller to show the page of starting/stopping a vote item or setting the profile of a vote item
 * @package Controllers\EA
 */
class VoteController extends Controller
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
        $data["items"] = VoteList::all()->count();
        $query = new Code();
        $data["voters"] = $query->select('public_key')->where([
            ['public_key', '<>', '0']
        ])->count();

        $votelist = VoteList::all()->where('is_started','1');

        return $this->view->render($response,'ea/dashboard.twig',['data'=>$data,'vote'=>$votelist]);

    }

    /**
     * VoteList page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getVoteList($request, $response)
    {
        $data = VoteList::all();

        return $this->view->render($response,'ea/vote.twig',['data'=>$data]);
    }

    /**
     * Add a Vote Page
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getAddVote($request, $response)
    {
        return $this->view->render($response,'ea/addVote.twig');
    }




    /**
     *
     * @param $request
     * @param $response
     * @return mixed
     */
    public function postVote($request,$response)
    {

        $validation = $this->validator->validate($request,[
            'title' => v::notEmpty(),
            'number' => v::noWhitespace()->positive()
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('ea.addVote'));
        }

        $vote_item =  VoteList::create([
            'title' => $request->getParam('title'),
            'number' => $request->getParam('number')
        ]);



//        $code = Code::useCode($user->id,$request->getParam('code'));

        return $response->withRedirect($this->router->pathFor('ea.vote'));
    }




}