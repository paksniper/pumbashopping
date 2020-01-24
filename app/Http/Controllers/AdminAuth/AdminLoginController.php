<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidLoginForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminLoginController extends Controller
{
    public function getLoginView() {
        return view('admin.admin_login');
    }

    public function getAdminHomeView() {
        return view('admin.home');
    }

    public function authenticateAdmin(ValidLoginForm $request) {
         $validLoginData = $request->validated();

        if(Auth::guard('admin')->attempt(['email'=>$request->input('admin_email'),'password'=>$request->input('admin_password')])) {
            return redirect()->intended('/');
        } else {
            return redirect(route('admin_login_view'))->with('error','Your password is incorrect');
        }
    }

    public function logoutAdmin() {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
