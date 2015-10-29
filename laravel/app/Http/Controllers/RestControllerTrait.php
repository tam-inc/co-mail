<?php
namespace App\Http\Controllers;

use App\Http\Response\RestResponse;

trait RestControllerTrait{

    protected function response(){

        return new RestResponse();

    }

    protected function responseOk( $data , $status=200 ){

        return $this->response()->ok( $data , $status );

    }

    protected function responseError( $data , $status=500 ){

        return $this->response()->error( $data , $status );

    }

    protected function responseBad( $data , $status=400 ){

        return $this->response()->bad( $data , $status );

    }

}

