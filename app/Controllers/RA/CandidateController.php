<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 2017/8/12
 * Time: 下午9:44
 */

namespace App\Controllers\RA;

use App\Controllers\Controller;
use App\Models\VoteList;

/**
 * Class CandidateAPIController
 * @author  Yifan Wu
 * The Candidate Controller to add, get ,edit, delete candidate
 * @package Controllers\RA
 */
class CandidateController extends Controller
{

    /**
     * Add candidate
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getAddCandidate($request, $response)
    {
        $result = VoteList::all();
        return $this->view->render($response,'ra/addCandidate.twig',['data'=>$result]);
    }

    /**
     * List && Edit candidate
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getCandidatesList($request, $response)
    {
        $result = VoteList::all();
        return $this->view->render($response,'ra/candidates.twig',['data'=>$result]);
    }
}