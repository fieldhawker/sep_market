<?php

namespace App\Http\Controllers\Api;

use Log;
use Util;
use Input;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\CybozuLiveService;

class CybozuGwBoardController extends Controller
{
    private $cybozu;

//    const SEP_GROUP_NAME = '(株)エス・イー・プロジェクト';
//    const SEP_TOPIC_NAME = 'いんふぉめーしょん';
    const SEP_GROUP_NAME = '自分用グループ';
    const SEP_TOPIC_NAME = 'メモするトピ';


    /**
     * CybozuGwBoardController constructor.
     *
     */
    public function __construct(CybozuLiveService $cybozu)
    {
        $this->cybozu = $cybozu;
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     */
    public function index()
    {
        abort(404);
    }

    public function create()
    {
        abort(404);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function store(Request $request)
    {
        Util::generateLogMessage('START');

        Util::postSlack(
          "store START : " . 
          $request["group_name"] . " : " . $request["topic_name"] . " : " . $request["text"]
        );

        $input = $this->getRequestParams($request);

        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
        ];

//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
//        ];

        $this->cybozu->setUser($user);
        $this->cybozu->setGroupName($input["group_name"]);
        $this->cybozu->setTopicName($input["topic_name"]);
        $this->cybozu->setMessage($input["text"]);

        $this->cybozu->postCgbMessage();
        Util::postSlack(
          $input["group_name"] . " : " . $input["topic_name"] . " : " . $input["text"]
        );

        Util::generateLogMessage('END');

        $response = ['result' => true];

        return $response;
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update($id)
    {
        abort(404);
    }

    public function destroy($id)
    {
        abort(404);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getRequestParams(Request $request)
    {
        $input = [];

        $param_setting = (
          $request["group_name"] &&
          $request["topic_name"] &&
          $request["text"]
        );

        if (!$param_setting) {
            abort(404);
        }

        $input["group_name"] = $request["group_name"];
        $input["topic_name"] = $request["topic_name"];
        $input["text"]       = $request["text"];

        Log::info('[store] 取得結果', ['input' => $input]);

        return $input;
    }
}
