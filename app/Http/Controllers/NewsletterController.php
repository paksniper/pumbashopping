<?php

namespace App\Http\Controllers;

use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribeToNewsletter(Request $request) {
        $validateNewsletterData = Validator::make($request->all(),[
           'email' => 'required|email:rfc,dns|unique:newsletters,email'
        ]);


        if($validateNewsletterData->passes()) {
            Newsletter::create([
                'email' => $request->input('email')
            ]);
            return response()->json(['success' => 'succefully subscription']);
        } else if($validateNewsletterData->fails()) {
            return response()->json(['fail' => $validateNewsletterData->errors()->first()]);
        }
    }
}
