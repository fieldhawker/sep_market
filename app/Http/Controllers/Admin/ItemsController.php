<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use Auth;
//use Hash;
use Input;
use Session;
use OperationLogsClass;
use App\Models\User;
use App\Models\Items;
use Config;
use App\Models\Exclusives;
//use App\OperationLogs;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class ItemsController
 * @package App\Http\Controllers\Admin
 */
class ItemsController extends Controller
{

    const MESSAGE_REGISTER_END    = 'register';
    const MESSAGE_UPDATE_END      = 'update';
    const MESSAGE_DELETE_END      = 'delete';
    const MESSAGE_NOT_FOUND_END   = 'not found';
    const MESSAGE_VALID_ERROR_END = 'error';
    const MESSAGE_MODIFIED_END    = 'modified';
    const SCREEN_NUMBER_REGISTER  = 410;
    const SCREEN_NUMBER_UPDATE    = 420;
    const SCREEN_NUMBER_DELETE    = 430;

    private $items;
    private $ope;
    private $exclusives;

    /**
     * ItemsController constructor.
     */
    public function __construct(Items $items, OperationLogsClass $ope, Exclusives $exclusives)
    {
        $this->middleware('auth:admin');

        $this->items      = $items;
        $this->ope        = $ope;
        $this->exclusives = $exclusives;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->items->query();
        $items = $query->orderBy('id', 'desc')->get();

//        $users = $query->orderBy('id','desc')->paginate(10);

        return view('admin.items.index')->with('items', $items);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.items.create');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $input                    = $this->getRequestParams($request);
        $input["user_id"]         = 1;

        $exception = DB::transaction(function () use ($input) {

            $id = $this->items->registerGetId($input);

            if ($id == false) {

                $errors = $this->items->errors();

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect('/admin/items/create/')
                  ->with('items', $this->items)
                  ->with('errors', $errors)
                  ->withInput();

            }

            Log::info('商品が登録されました。', ['id' => $id]);

            $data = [
              'screen_number' => self::SCREEN_NUMBER_REGISTER,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            Session::flash('message', self::MESSAGE_REGISTER_END);

            return redirect('/admin/items');

        });

        return $exception;

    }


    /**
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {

        // レコードを検索

        $query = $this->items->query();
        $item  = $query
          ->join('users', 'items.user_id', '=', 'users.id')
          ->where('items.id', '=', $id)
          ->orderBy('items.id', 'desc')
          ->select('items.*', 'users.name AS uname')
          ->first();

        if (!$item) {
            return redirect('/admin/items');
        }

        //検索結果をビューに渡す
        return view('admin.items.show')
          ->with('item', $item);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return $this
     */
    public function edit(Request $request, $id)
    {

        // レコードを検索

        $item = $this->items->findOrFail($id);

        if (!$item) {
            return redirect('/admin/items');
        }

        // 他の管理者が編集中か

        $data = [
          'screen_number' => self::SCREEN_NUMBER_UPDATE,
          'target_id'     => $id,
          'operator'      => Auth::guard("admin")->user()->id,
        ];

        $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($data);

        if (!$is_exclusives) {

            // 編集中にする

            $data["expired_at"] = date("Y/m/d H:i:s", strtotime(Config::get('const.exclusives_time')));
            $exclusives_id      = $this->exclusives->insertGetId($data);

        }

        //検索結果をビューに渡す
        return view('admin.items.update')
          ->with('item', $item)
          ->with('is_exclusives', $is_exclusives);

    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $input                    = $this->getRequestParams($request);

        $exception = DB::transaction(function () use ($input, $id) {

            // 他の管理者が編集中か

            $exclusives = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
            ];

            $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($exclusives);

            if ($is_exclusives) {

                $url = sprintf('/admin/items/%s/edit/', $id);

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect($url)
                  ->with('items', $this->items)
                  ->withInput();

            }

            // 存在チェック

            $item = $this->items->find($id);

            if (!$item) {

                Session::flash('message', self::MESSAGE_NOT_FOUND_END);

                return redirect('/admin/items/');

            }

            // 更新を開始

            $result = $this->items->updateItems($input, $id);

            if ($result == false) {

                $errors = $this->items->errors();
                $url    = sprintf('/admin/items/%s/edit/', $id);

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect($url)
                  ->with('item', $item)
                  ->with('errors', $errors)
                  ->withInput();

            }

            Log::info('商品が更新されました。', ['id' => $id]);

            // 排他制御を削除

            $result = $this->exclusives->deleteExpiredByMine($exclusives);

            // 操作ログを記録

            $data = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            Session::flash('message', self::MESSAGE_UPDATE_END);

            return redirect('/admin/items');

        });

        return $exception;

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        $exception = DB::transaction(function () use ($id) {

            // 他の管理者が編集中か

            $exclusives = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
            ];

            $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($exclusives);

            if ($is_exclusives) {

                Session::flash('message', self::MESSAGE_MODIFIED_END);

                return redirect('/admin/items/');

            }

            // 削除対象レコードを検索
            $item = $this->items->find($id);

            // 論理削除
            $item->delete();

            Log::info('商品が削除されました。', ['id' => $id]);

            $data = [
              'screen_number' => self::SCREEN_NUMBER_DELETE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($item, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            return redirect()->to('/admin/items')->with('message', self::MESSAGE_DELETE_END);

        });

        return $exception;

    }

    /**
     * @param Request $request
     * @param         $input
     *
     * @return mixed
     */
    public function getRequestParams(Request $request)
    {
        $input = [];
        
        $input["name"]            = $request->name;
        $input["price"]           = $request->price;
        $input["caption"]         = $request->caption;
        $input["status"]          = $request->status;
        $input["items_status"]    = $request->items_status;
        $input["started_at"]      = $request->started_at;
        $input["ended_at"]        = $request->ended_at;
        $input["delivery_charge"] = $request->delivery_charge;
        $input["delivery_plan"]   = $request->delivery_plan;
        $input["pref"]            = $request->pref;
        $input["delivery_date"]   = $request->delivery_date;
        $input["comment"]         = $request->comment;

        return $input;
    }
}
