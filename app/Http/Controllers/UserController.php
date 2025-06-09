<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function wallet()
    {
        return view('user.wallet', ['user' => Auth::user()]);
    }
}
