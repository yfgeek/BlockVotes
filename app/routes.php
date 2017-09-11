<?php
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\RAMiddleware;
use App\Middleware\EAMiddleware;
use App\Middleware\VoterMiddleware;

/**
 * The routes of all pages
 * @author  Yifan Wu
 */

/**
 * Public Page
 */
$app->get('/','HomeController:index')->setName('home');

// Public API
$app->get('/api/bitcoinaddress','PublicAPIController:getBitcoinAddress');
$app->get('/api/publickey','PublicAPIController:getAllPubKeys');
$app->get('/api/getcandidates','CandidateAPIController:getCandidates');
$app->get('/api/sighash','PublicAPIController:getSigPair');
$app->post('/api/sigpairs','PublicAPIController:postSigPair');
$app->get('/api/eaaddress','PublicAPIController:getEABitcoinAddress');


$app->get('/vote/fail','VoterController:getFailed')->setName('vote.fail');
$app->get('/verify/home','VoterController:getVerifyHome')->setName('verify.home');
$app->get('/verify/now','VoterController:getVerify')->setName('verify.now');
$app->get('/tally/home','VoterController:getTallyHome')->setName('tally.home');
$app->get('/tally/now','VoterController:getTally')->setName('tally.now');

/**
 * Public page for voters
 */
$app->group('',function () {
    $this->get('/vote/start','VoterController:getStarted')->setName('vote.start');
    $this->get('/vote/home','VoterController:getVote')->setName('vote.home');
    $this->get('/vote/wait','VoterController:getWait')->setName('vote.wait');
    $this->get('/vote/fill','VoterController:getFillForm')->setName('vote.fill');
    $this->get('/vote/lost','VoterController:getCandidate')->setName('vote.lost');
    $this->get('/voter/vote','VoterController:getVoteList')->setName('voter.vote');
    $this->get('/vote/page','VoterController:getCandidate')->setName('vote.vote');

    //API
    $this->get('/api/postpubkey','PublicAPIController:postPubKey');
    $this->get('/api/bitcoinkey','PublicAPIController:getBitCoinPriv');


})->add(new VoterMiddleware($container));


/**
 * Public page
 */
$app->group('',function () {
    $this->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup','AuthController:postSignUp');
	$this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin','AuthController:postSignIn');

})->add(new GuestMiddleware($container));


/**
 * Must login Page
 */
$app->group('',function () {
    $this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');
	$this->get('/auth/password/change','PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/auth/password/change','PasswordController:postChangePassword');
    $this->post('/auth/setting','EASettingController:postSetting');
    $this->get('/auth/dashboard','AuthController:getDashboard')->setName('auth.dashboard');

})->add(new AuthMiddleware($container));

/**
 * Must login as RA
 */
$app->group('',function () {
    // API
    $this->get('/api/addvoter','BallotAPIController:postAddVoter');
    $this->get('/api/addcandidate','CandidateAPIController:postAddCandidate');
    $this->get('/api/updatecandidate','CandidateAPIController:postUpdateCandidate');
    $this->get('/api/delcandidate','CandidateAPIController:postDelCandidate');

    $this->get('/ra/addVoter','BallotController:getAddVoter')->setName('ra.addVoter');
    $this->get('/ra/ballot','BallotController:getBallot')->setName('ra.ballot');
    $this->post('/ra/ballot','BallotController:postBallot');


    $this->get('/ra/addcandidate','CandidateController:getAddCandidate')->setName('ra.addCandidate');;


    $this->get('/ra/dashboard','BallotController:getDashboard')->setName('ra.dashboard');

    $this->get('/ra/candidates','CandidateController:getCandidatesList')->setName('ra.candidates');


})->add(new RAMiddleware($container));

/**
 * Must login as EA
 */
$app->group('',function () {
    // API
    $this->get('/api/keyslist','VoteAPIController:getKeysList');
    $this->get('/api/togglevote','VoteAPIController:getToggleVoting');
    $this->get('/api/voteprofile','VoteAPIController:getProfile');
    $this->post('/api/updateprofile','VoteAPIController:postProfile');
    $this->get('/api/allbitcoinaddress','PublicAPIController:getAllBitcoinAddress');

    $this->get('/ea/vote','VoteController:getVoteList')->setName('ea.vote');
    $this->get('/ea/addVote','VoteController:getAddVote')->setName('ea.addVote');
    $this->post('/ea/vote','VoteController:postVote');


    $this->get('/ea/balance','BlockChainController:getBalanceList')->setName('ea.balance');
    $this->get('/ea/fee','BlockChainController:getFeeList')->setName('ea.fee');

    $this->get('/ea/dashboard','VoteController:getDashboard')->setName('ea.dashboard');
    $this->get('/ea/setting','EASettingController:getSetting')->setName('ea.setting');
    $this->post('/ea/setting','EASettingController:postSetting');

})->add(new EAMiddleware($container));


