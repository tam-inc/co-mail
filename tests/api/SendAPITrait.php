<?php
namespace Tests\Api;

trait SendAPITrait
{
    public function sendAPI($url,$data=[],$status=200){
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