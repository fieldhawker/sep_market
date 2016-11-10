<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Log;
use Auth;
use OperationLogsClass;

use App\Models\Exclusives;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExclusivesController extends Controller
{

    const MESSAGE_DELETE_END    = 'delete';
    const MESSAGE_NOT_FOUND_END = 'not found';
    const SCREEN_NUMBER_DELETE  = 330;

    private $exclusives;
    private $operatin_logs;

    /**
     * ExclusivesController constructor.
     *
     * @param OperationLogsClass $ope
     * @param Exclusives         $exclusives
     */
    public function __construct(OperationLogsClass $ope, Exclusives $exclusives)
    {
        $this->middleware('auth:admin');

        $this->operatin_logs = $ope;
        $this->exclusives    = $exclusives;
    }

    public function index()
    {
        $query = $this->exclusives->query();
        $exclusives = $query
          ->join('users', 'exclusives.operator', '=', 'users.id')
          ->where('exclusives.expired_at', '>', date('Y/m/d H:i:s'))
          ->orderBy('id', 'desc')
          ->select('exclusives.*', 'users.name')
          ->get();

        return view('admin.exclusives.index')->with('exclusives', $exclusives);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect('/admin/exc');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        return redirect('/admin/exc');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect('/admin/exc');
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(Request $request, $id)
    {
        return redirect('/admin/exc');
    }


    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        return redirect('/admin/exc');
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {

        $exception = DB::transaction(function () use ($id) {

            // 削除対象レコードを検索
            $exclusives = $this->exclusives->find($id);

            // 削除
            $exclusives->delete();

            Log::info('排他制御が削除されました。', ['id' => $id]);

            $data = [
              'screen_number' => self::SCREEN_NUMBER_DELETE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($exclusives, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->operatin_logs->registerGetId($data);

            Log::info('排他制御が登録されました。', ['id' => $id]);

            return redirect()->to('/admin/exc')->with('message', self::MESSAGE_DELETE_END);

        });

        return $exception;

    }
}
