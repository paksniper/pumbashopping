<?php

namespace App\Http\Controllers\AdminAuth;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Mail\newsletter;
use App\Mail\NewsLetterMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminForgotPasswordController extends Controller
{
    public function getForgotPasswordview()
    {
        return view('admin.admin_forgot_password_view');
    }

    public function getChangePasswordview(Request $request,$email)
    {
        return view('admin.admin_change_password_view')->with('email',$email);
    }

    public function emailForgotPassword(Request $request)
    {
        $validateData = $request->validate([
            'admin_email' => 'required|email:rfc,dns|exists:admins,email'
        ]);
        $email = $request->input('admin_email');
//        Mail::to($email)->send(new NewsLetterMail($email));
        Mail::to($email)->send(new ForgotPassword($email));
        return back()->with('status', 'An email has been send to the provided email address,Thank you');
    }

    public function changeForgotPassword(Request $request)
    {
        $validatePassword = $request->validate(
            [
                'new_password' => 'required|min:5',
                'confirm_password' => 'required|min:5|same:new_password'
            ]
        );
        Admin::where('email',$request->input('admin_email'))->update([
            'password' => Hash::make($request->input('new_password'))
        ]);
        return redirect(route('admin_login_view'))->with(['status' => 'Password successfully has been updated']);
    }
}
