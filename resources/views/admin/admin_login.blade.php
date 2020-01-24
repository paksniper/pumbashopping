@extends('layout.app')

@section('title')
    Admin Login
@endsection

@section('content')
    @if(\Illuminate\Support\Facades\Session::has('status'))
        <div class="mt-3 p-3 text-center alert alert-success alert-dismissible show fade">
            {{\Illuminate\Support\Facades\Session::get('status')}}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    <div class="container my-5">
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div
                        class="card-header border border-top-0 border-left-0 border-right-0 border-bottom bg-transparent">
                        <div class="card-title">
                            <h5 class="text-center">Login</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{route('post_admin_login')}}"
                            method="post" enctype="application/x-www-form-urlencoded"
                            name="admin_registration_form"
                            id="admin_registration_form">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="admin_email"
                                       class="form-control @error('admin_email') is-invalid @enderror"
                                       placeholder="Enter email here" value="{{old('admin_email')}}">
                                @error('admin_email')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="admin_password"
                                       class="form-control @error('admin_password') is-invalid @enderror"
                                       placeholder="Enter password here">
                                @error('admin_password')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                                @if(session('error'))
                                    <div
                                        style="color: #e70000; font-size: 14px;padding: 2px;">{{session('error')}}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-outline-success btn-block mb-2" value="Login">

                                <a href="{{route('admin_forgot_password_view')}}" class="forgot-password-btn">Forgot your password ?</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
