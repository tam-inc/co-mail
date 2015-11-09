<?php 
$I = new ApiTesterEx($scenario);
$I->wantTo('ユーザーがログインしているかどうか');

codecept_debug("hoge");

// 異常系試験
$I->haveFriend('RICE_ME')->does(function(ApiTester $I){

    $I->amGoingTo("ユーザー取得");
    $I->sendGET('/rice/me');

    $I->seeResponseContainsJson([
        'status'=>'BAD',
        "message" => "ログインしていません。",
    ]);




});

