<?php


namespace app\Controllers\RA;

use App\Controllers\Controller;
use App\Models\Candidate;

/**
 * Class CandidateAPIController
 * @author  Yifan Wu
 * The Candidate API Controller to add, get ,edit, delete candidate
 * @package Controllers\RA
 */
class CandidateAPIController extends Controller
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