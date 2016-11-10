<?php

namespace App\Http\Controllers\Admin;

use Log;
use Util;
use Input;
use Session;
use App\Services\UsersService;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\Admin
 *
 */
class UsersController extends Controller
{

    const MESSAGE_REGISTER_END    = 'register';
    const MESSAGE_UPDATE_END      = 'update';
    const MESSAGE_DELETE_END      = 'delete';
    const MESSAGE_NOT_FOUND_END   = 'not found';
    const MESSAGE_VALID_ERROR_END = 'error';
    const MESSAGE_MODIFIED_END    = 'modified';

    private $users;

    /**
     * UsersController constructor.
     *
     */
    public function __construct(UsersService $users)
    {
        $this->users = $users;

        $this->middleware('auth:admin');

    }

    /**
     * 会員一覧画面を表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Util::generateLogMessage('START');

        // ----------------------------
        // 会員情報をすべて取得する
        // ----------------------------

        $users = $this->users->findAll();

        Util::generateLogMessage('END');

        return view('admin.users.index')->with('users', $users);
    }

    /**
     * 会員登録画面を表示する
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Util::generateLogMessage('START');

        // ----------------------------
        // 
        // ----------------------------

        Util::generateLogMessage('END');

        return view('admin.users.create');
    }

    /**
     * 会員を登録する
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        Util::generateLogMessage('START');

        // ----------------------------
        // リクエストパラメータを取得
        // ----------------------------

        $input = $this->users->getRequest($request);

        // ----------------------------
        // バリデーション
        // ----------------------------

        if (!$this->users->validate($input)) {

            $errors = $this->users->getErrors();

            Session::flash('message', self::MESSAGE_VALID_ERROR_END);

            Util::generateLogMessage('END 入力内容に不備がありました');

            return redirect('/admin/users/create/')
              ->with('user', $input)
              ->with('errors', $errors)
              ->withInput();

        }

        // ----------------------------
        // 会員を登録する
        // ----------------------------

        $exception = $this->users->registerUser($input);

        if ($exception) {

            Util::generateLogMessage('END 会員の登録に失敗しました');

            return $exception;

        }

        Session::flash('message', self::MESSAGE_REGISTER_END);

        Util::generateLogMessage('END');

        return redirect('/admin/users');

    }


    /**
     * 会員情報の詳細を取得する
     *
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {

        Util::generateLogMessage('START');

        // ----------------------------
        // 会員情報をIDから検索する
        // ----------------------------

        $user = $this->users->findById($id);

        if (!$user) {

            // ----------------------------
            // 見つからなかったら一覧に戻る
            // ----------------------------

            Session::flash('message', self::MESSAGE_NOT_FOUND_END);

            Util::generateLogMessage('END 指定のIDの会員が存在しません');

            return redirect('/admin/users');

        }

        Util::generateLogMessage('END');

        // ----------------------------
        //検索結果をビューに渡す
        // ----------------------------

        return view('admin.users.show')
          ->with('user', $user);
    }

    /**
     * 会員情報の編集画面を表示する
     *
     * @param         $id
     *
     * @return $this
     */
    public function edit($id)
    {

        Util::generateLogMessage('START');

        // ----------------------------
        // 会員情報をIDから検索する
        // ----------------------------

        $user = $this->users->findById($id);

        if (!$user) {

            // ----------------------------
            // 見つからなかったら一覧に戻る
            // ----------------------------

            Util::generateLogMessage('END 指定のIDの会員が存在しません');

            return redirect('/admin/users');

        }

        // ----------------------------
        // 他の管理者が編集中か
        // ----------------------------

        $is_exclusives = $this->users->isExpiredByOtherAdmin($id);

        Util::generateLogMessage('END');

        // ----------------------------
        //検索結果をビューに渡す
        // ----------------------------

        return view('admin.users.update')
          ->with('user', $user)
          ->with('is_exclusives', $is_exclusives);

    }

    /**
     * 会員情報を更新する
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        Util::generateLogMessage('START');

        // ----------------------------
        // リクエストパラメータを取得
        // ----------------------------

        Log::info('入力されたパラメータ', ['id' => $id]);

        $input = $this->users->getRequest($request);

        // ----------------------------
        // バリデーション
        // ----------------------------

        if (!$this->users->validate($input, $id)) {

            $errors = $this->users->getErrors();

            $url = sprintf('/admin/users/%s/edit/', $id);

            Session::flash('message', self::MESSAGE_VALID_ERROR_END);

            Util::generateLogMessage('END 入力内容に不備がありました');

            return redirect($url)
              ->with('user', $input)
              ->with('errors', $errors)
              ->withInput();

        }

        // ----------------------------
        // 存在チェック
        // ----------------------------

        $user = $this->users->findById($id);

        if (!$user) {

            // ----------------------------
            // 見つからなかったら一覧に戻る
            // ----------------------------

            Session::flash('message', self::MESSAGE_NOT_FOUND_END);

            Util::generateLogMessage('END 指定のIDの会員が存在しません');

            return redirect('/admin/users/');

        }

        // ----------------------------
        // 他の管理者が編集中か
        // ----------------------------

        $is_exclusives = $this->users->isExpiredByOtherAdmin($id);

        if ($is_exclusives) {

            $url = sprintf('/admin/users/%s/edit/', $id);

            return redirect($url)
              ->with('user', $input)
              ->withInput();

        }

        // ----------------------------
        // 会員を更新する
        // ----------------------------

        $exception = $this->users->updateUser($input, $id);

        if ($exception) {

            Util::generateLogMessage('END 会員の更新に失敗しました');

            return $exception;

        }

        Session::flash('message', self::MESSAGE_UPDATE_END);

        Util::generateLogMessage('END');

        return redirect('/admin/users');

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Util::generateLogMessage('START');

        Log::info('入力されたパラメータ', ['id' => $id]);

        // ----------------------------
        // 他の管理者が編集中か
        // ----------------------------

        $is_exclusives = $this->users->isExpiredByOtherAdmin($id);

        if ($is_exclusives) {

            Session::flash('message', self::MESSAGE_MODIFIED_END);

            return redirect('/admin/users/');

        }

        // ----------------------------
        // 会員を削除する
        // ----------------------------

        $exception = $this->users->deleteUser($id);

        if ($exception) {

            Util::generateLogMessage('END 会員の削除に失敗しました');

            return $exception;

        }

        Util::generateLogMessage('END');

        return redirect()->to('/admin/users')->with('message', self::MESSAGE_DELETE_END);

    }
}
