@extends('layout.app')

@section('title')
    Change Password
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
                    <input type="hidden" name="admin_email" value="{{$email}}">
                    <div
                        class="card-header border border-top-0 border-left-0 border-right-0 border-bottom bg-transparent">
                        <div class="card-title">
                            <h5 class="text-center">Change Password</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{route('post_admin_change_password')}}"
                            method="post" enctype="application/x-www-form-urlencoded"
                            name="admin_registration_form"
                            id="admin_registration_form">
                            @csrf
                            <input type="hidden" name="admin_email" value="{{$email}}">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password"
                                       class="form-control @error('new_password') is-invalid @enderror"
                                       placeholder="Enter new password here">
                                @error('new_password')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password"
                                       class="form-control @error('confirm_password') is-invalid @enderror"
                                       placeholder="Enter confirm password here">
                                @error('confirm_password')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-outline-success btn-block mb-2" value="Change password">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
