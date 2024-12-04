<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{
    public function create()
    {
        return view('auth.first-login');
    }

    public function store(PasswordRequest $request)
    {
        auth()->user()->update([
            'first_login' => 1,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.dashboard.index');
    }
}
