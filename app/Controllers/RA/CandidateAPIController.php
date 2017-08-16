<?php


namespace app\Controllers\RA;

use App\Controllers\Controller;
use App\Models\Candidate;


class CandidateAPIController extends Controller
{

    public function generateJson($response, $success, $message, $param = null)
    {
        $return_message["success"] = $success;
        $return_message["content"] = $message;
        $return_message["param"] = $param;
        return $response->withJson($return_message);
    }


    /**
     * @param $request
     * @param $response
     * @return as
     */
    public function getCandidates($request, $response)
    {
        $data = Candidate::where('vote_id', (int)$request->getParam('vote_id'))->get();
        return $this->generateJson($response, '1', $data, '');
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function postAddCandidate($request, $response)
    {
        $candidate = new Candidate();
        $candidate->addCandidate(
            $request->getParam('name'),
            $request->getParam('des'),
            (int)$request->getParam('vote_id')
        );
        return $this->generateJson($response, '1', 'This candidate has been added', '');
    }

    /**
     * @param $request
     * @param $response
     * @return as
     */
    public function postUpdateCandidate($request, $response)
    {
        $candidate = new Candidate();
        $query = $candidate->where('id', (int)$request->getParam('id'))->first();
        if(!$query)
            return $this->generateJson($response, '0', 'The candidate dose not exist', '');

        $query->updateCandidate(
                $request->getParam('name'),
                $request->getParam('des')
            );
        return $this->generateJson($response, '1', 'This candidate has been updated', '');
    }

    public function postDelCandidate($request, $response)
    {
        $candidate = new Candidate();
        $candidate->delCandidate(
                (int)  $request->getParam('id')
            );
        return $this->generateJson($response, '1', 'This candidate has been updated', '');
    }
}