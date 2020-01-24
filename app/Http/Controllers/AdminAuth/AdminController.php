<?php

namespace App\Http\Controllers\AdminAuth;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getAdminProfileView($id) {
        $admin = Admin::find($id);
        return view('admin.admin_edit_profile')->with(['admin' => $admin]);
    }

    public function postEditProfile(Request $request , $id) {
        $validateAdminRegistrationData = $request->validate(
            [
                'admin_name' => 'required|alpha_num|min:5|unique:admins,name',
                'admin_email' => 'required|email:rfc,dns|unique:admins,email',
                'current_password' => 'required|min:5',
                'password' => 'required|min:5',
                'confirm_password' => 'required|min:5|same:password',
                'admin_image' => 'nullable|image|mimes:jpeg|dimensions:min_width=200,
                max_width=200,min_height=200,max_height=200'
            ]
        );

        $admin_current = Admin::find($id);
//        dd($admin_current);
        if(!$admin_current->password === Hash::check($request->input('current_password'),$admin_current->password)) {
            return redirect(route('admin_profile_view',$id))->with('error','Your current password is incorrect');
        }
        $image = null;
        $image_name = $admin_current->image;
        if($request->hasFile('admin_image')) {
            if (File::exists(storage_path('app/public/images/admin/' . $admin_current->image))) {
                File::delete(storage_path('app/public/images/admin/' . $admin_current->image));
            }
            $image = $request->file('admin_image');
            $image->store('public/images/admin/');
            $image_name = $image->hashName();
        }
        Admin::where('id',$id)->update([
            'name' => $request->input('admin_name'),
            'email' => $request->input('admin_email'),
            'password' => Hash::make($request->input('password')),
            'image' => $image_name,
        ]);

        return redirect(route('admin_profile_view',$id))->with(['status' => 'Profile successfully has been updated']);

    }

}
