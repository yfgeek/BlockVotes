<?php

namespace App\Controllers\EA;


use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
/**
 * Class EASettingController
 * @author  Yifan Wu
 * The EASetting Controller to show the page of setting in EA users
 * @package Controllers\EA
 */
class EASettingController extends Controller
{
    /**
     * user setting Pages
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getSetting($request,$response)
    {
        $data = $this->auth->user();
        return $this->view->render($response,'ea/setting.twig',['user'=>$data]);
    }

    /**
     * POST: setting pages
     * @param $request address,pubckey
     * @param $response
     * @return mixed
     */
    public function postSetting($request,$response)
    {
//        $validation = $this->validator->validate($request,[
//            'address' => v::noWhitespace()->notEmpty()->regex('/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/'),
//            'pubkey' => v::noWhitespace()->notEmpty()->regex('/-----BEGIN PUBLIC KEY-----\n(.*)\n-----END PUBLIC KEY-----/s'),
//        ]);
//
//        if ($validation->failed()) {
//            return $response->withRedirect($this->router->pathFor('auth.setting'));
//        }
        $this->auth->user()->setBitcoinAddress($request->getParam('address'));
        $this->auth->user()->setPrivkey($request->getParam('pubkey'));


        $this->flash->addMessage('info','Your setting was changed');

        return $response->withRedirect($this->router->pathFor('ea.setting'));

    }

}