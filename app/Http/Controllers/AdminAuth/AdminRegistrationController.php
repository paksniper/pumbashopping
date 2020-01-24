<?php

namespace App\Http\Controllers\AdminAuth;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminRegistrationController extends Controller
{

    public function getRegistrationView() {
        return view('admin.admin_registration');
    }

    public function registerAsAdmin(Request $request) {
        $validateAdminRegistrationData = $request->validate(
            [
                'admin_name' => 'required|alpha_num|min:5',
                'admin_email' => 'required|email:rfc,dns|unique:admins,email',
                'admin_password' => 'required|min:5',
                'confirm_password' => 'required|min:5|same:admin_password',
                'admin_image' => 'required|image|mimes:jpeg|dimensions:min_width=200,
                max_width=200,min_height=200,max_height=200'
            ]
        );
        if(!File::isDirectory(storage_path('app/public/images/admin'))) {
            File::makeDirectory(storage_path('app/public/images/admin'));
        }
        $image = $request->file('admin_image');
        $image->store('public/images/admin/');
        $password = $request->input('admin_password');
        $name = $request->input('admin_name');
        $email = $request->input('admin_email');

        Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'image' => $image->hashName()
        ]);

        return redirect(route('admin_registration_view'))->with(['status' => 'Please check your email and activate your account']);
    }

}
