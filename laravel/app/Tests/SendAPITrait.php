<?php
namespace App\Tests;

trait SendAPITrait
{
    public function sendGetAPI($url,$data=[],$status=200){
        $I = $this;
        $I->haveHttpHeader("Content-Type","application/json");
        $I->sendGET("{$this->entry}$url",$data);
        if($status){
            $I->SeeResponseCodeIs($status);
        }
        $I->seeResponseIsJson();
    }

    public function sendPostAPI($url,$data=[],$status=200){
        $I = $this;
        $I->haveHttpHeader("Content-Type","application/json");
        $I->sendPOST("{$this->entry}$url",$data);
        if($status){
            $I->SeeResponseCodeIs($status);
        }
        $I->seeResponseIsJson();
    }

    public function grabResponseJson(){
        $I = $this;
        $I->seeResponseIsJson();
        return json_decode($I->grabResponse(),true);
    }


}