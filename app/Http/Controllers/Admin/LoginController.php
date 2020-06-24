<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
class LoginController extends Controller
{
    protected function getLogin()
    {
        return view('admin.Auth.login');
    }

    protected function login(LoginRequest $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')
            ->attempt(['email' => $request->input("email"),
                'password' => $request->input("password")],
                $remember_me)) { // successfully logged in
            // notify()->success('تم الدخول بنجاح  ');
            return redirect() -> route('admin.dashboard');
        }
        // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);
    }

    protected  function logout(){

    }
}


