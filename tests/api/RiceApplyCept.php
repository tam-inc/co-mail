<?php 
$I = new ApiTester($scenario);
$I->wantTo('ユーザーがログインしているかどうか');

//$I->amGoingTo("ユーザー取得");
//$I->sendPOST('/rice/apply');

$I->sendPOST("/rice/apply",[
    "id" => 3,
    "rice" => 2
]);

//$response = $I->grabResponseJson();

$I->seeResponseIsJson();

codecept_debug($I->seeResponseIsJson());
//
//
//$I->seeResponseContainsJson([
//    'status'=>'OK'
//]);
//$I->seeResponseJsonMatchesJsonPath('$.response.result');
//$I->haveFriend('GET_USER')->does(function(ApiTesterEx $I){
//    $I->amGoingTo('POST Test');
//        $I->sendPOST('hoge');
//    $I->sendPOST('rice/me');
//
////    $I->seeResponseContainsJson(array('result'=>0));
////    $I->seeResponseJsonMatchesJsonPath('$.response.result');
//});
