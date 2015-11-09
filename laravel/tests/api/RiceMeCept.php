<?php 
$I = new ApiTesterEx($scenario);
$I->wantTo('getUser');

$I->haveFriend('RICE_ME')->does(function(ApiTester $I){

    $I->amGoingTo("ユーザー取得");
    $I->sendGetAPI('/me',[],400);

    $I->seeResponseContainsJson([
        'status'=>'BAD',
        "message" => "ログインしていません。",
    ]);

});

