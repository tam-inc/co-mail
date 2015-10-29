<?php

namespace App\Http\Controllers;

use App\Service\RiceService;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Socialite;
use Session;
use Request;
use DB;

class RiceController extends Controller
{

    protected function getUser(UserService $userService)
    {

        $id = Session::get('auth');

        $user = $userService->getUser($id);

        return $user;

    }

    protected function apply(RiceService $riceService)
    {

        $params = [
//            'id' => Session::get('auth'),
            'id' => Request::input('id'),
            'rice' => Request::input('rice'),
        ];

        if($params['id'] && $params['rice']){

            $result = $riceService->apply($params);

            return $result;

        } else {

            return JsonResponse::create([
                "status" => "BAD",
                "message" => "入力値が正しくありません。",
            ],400);

        }

    }

    protected function today(RiceService $riceService)
    {

        $result = $riceService->today();

        return $result;

    }

    protected function getTodayWinner(RiceService $riceService)
    {

        $result = $riceService->getTodayWinner();

        return [$result];

    }

}

