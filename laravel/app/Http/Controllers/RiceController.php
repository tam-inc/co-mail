<?php

namespace App\Http\Controllers;

use App\Service\RiceService;
use App\Service\UserService;
use Socialite;
use Session;
use Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
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

        $params = array();
        $params['id'] = Request::input('id');
        $params['rice'] = Request::input('rice');

        $riceService->createApplyTable($params);

        return json_encode([
            "status" => "OK",
        ]);

    }

}

