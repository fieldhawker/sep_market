<?php

namespace App\Http\Controllers\Api;

use Log;
use Util;
use Input;
use Config;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\CybozuLiveService;

class CybozuGwBoardJsonController extends Controller
{
    private $cybozu;

//    const SEP_GROUP_NAME = '(株)エス・イー・プロジェクト';
//    const SEP_TOPIC_NAME = 'いんふぉめーしょん';
    const SEP_GROUP_NAME = '自分用グループ';
    const SEP_TOPIC_NAME = 'メモするトピ';


    /**
     * CybozuGwBoardJsonController constructor.
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

//        {
//            "access_key": "あらゆ",
//            "group_name": "男",
//            "topic_name": "",
//            "text": "SYNCER",   
//        }

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
          "SEND DATA : " . PHP_EOL .
          $input["access_key"] . PHP_EOL .
          $input["group_name"] . PHP_EOL .
          $input["topic_name"] . PHP_EOL .
          $input["text"]
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

        Util::postSlack(
          "POST DATA : " . PHP_EOL .
          $request->input('access_key') . PHP_EOL .
          $request->input('group_name') . PHP_EOL .
          $request->input('topic_name') . PHP_EOL .
          $request->input('text')
        );

        $param_setting = (
          $request->has('access_key') &&
          $request->has('group_name') &&
          $request->has('topic_name') &&
          $request->has('text')
        );

        if (!$param_setting) {
            abort(404);
        }

        $input["access_key"] = $request->input('access_key');
        
        $keys = Config::get('const.cgbj_key');
        if (array_search($input["access_key"], $keys) === false) {
            abort(404);
        }

        $input["group_name"] = $request->input('group_name');
        $input["topic_name"] = $request->input('topic_name');
        $input["text"]       = $request->input('text');

        Log::info('[store] 取得結果', ['input' => $input]);

        return $input;
    }
}
