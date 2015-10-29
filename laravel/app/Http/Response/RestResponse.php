<?php
namespace App\Http\Response;

use Illuminate\Http\JsonResponse;

class RestResponse {

    public function ok( array $data , $status=200 ){

        $data[ "status" ] = "OK";

        return new JsonResponse( $data , $status , $this->restHeader() );

    }

    public function bad(array $data , $status=400){

        $data[ "status" ] = "BAD";

        return new JsonResponse( $data , $status , $this->restHeader() );

    }

    public function error(array $data , $status=500){

        $data[ "status" ] = "ERROR";

        return new JsonResponse( $data , $status , $this->restHeader() );

    }

    protected function restHeader(){

        $request = app("request");

        return [

            "Access-Control-Allow-Credentials" => "true",
            "Access-Control-Allow-Origin"      => "*",
            'Access-Control-Allow-Headers'     => $request->header('Access-Control-Request-Headers'),
            "Access-Control-Allow-Methods"     => "GET,POST"

        ];
    }

}