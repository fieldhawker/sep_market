<?php

namespace App\Http\Controllers\UsersAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/users/';
    protected $linkRequestView = 'usersAuth.passwords.email';
    protected $resetView = 'usersAuth.passwords.reset';
    protected $guard = 'users';
    protected $broker = 'users';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:users');
    }
}
