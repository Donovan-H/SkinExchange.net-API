<?php

namespace App\Http\Controllers;

use App\User as User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function get($id)
    {
        return User::findorfail($id);
    }
    //
}
