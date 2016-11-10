<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\OperationLogs;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class OperationLogsController
 * @package App\Http\ControllersAdmin
 */
class OperationLogsController extends Controller
{

    private $ope;

    /**
     * OperationLogsController constructor.
     *
     * @param OperationLogs $ope
     */
    public function __construct(OperationLogs $ope)
    {
        $this->middleware('auth:admin');

        $this->ope = $ope;
    }

    /**
     * @return $this
     */
    public function index()
    {
        $query          = $this->ope->query();
        $operation_logs = $query
          ->join('admins', 'operation_logs.operator', '=', 'admins.id')
          ->orderBy('operation_logs.id', 'desc')
          ->select('operation_logs.*', 'admins.name')
          ->take(1000)->get();

        return view('admin.operationLogs.index')->with('operation_logs', $operation_logs);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect('/admin/ope');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        return redirect('/admin/ope');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect('/admin/ope');
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(Request $request, $id)
    {
        return redirect('/admin/ope');
    }


    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        return redirect('/admin/ope');
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        return redirect('/admin/ope');
    }
}
