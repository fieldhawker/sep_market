<?php

namespace App\Http\Controllers\Admin;

use App\Models\Items;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends Controller
{

    private $user;
    private $items;
    
    /**
     * HomeController constructor.
     */
    public function __construct(User $user, Items $items)
    {
        $this->middleware('auth:admin');
        $this->user = $user;
        $this->items = $items;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_count = $this->user->count();
        $items_count = $this->items->count();

        return view('admin.home')->with('user_count', $user_count)->with('items_count', $items_count);
    }
}
