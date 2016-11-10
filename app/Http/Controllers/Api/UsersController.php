<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    /**
     * UsersController constructor.
     * 
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * 
     */
    public function index()
    {
        $users = User::all()->toJson();

        return $users;
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
