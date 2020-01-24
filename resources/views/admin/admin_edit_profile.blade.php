@extends('layout.admin_app')

@section('title')
    Edit Profile
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
    <div class="container">
        <div class="row">
            <div class="card w-100 my-5">
                <div class="card-header">
                    <div class="card-title">
                        Update your profile
                    </div>
                </div>
                <div class="card-body p-5">
                    <form action="{{route('post_edit_admin_profile',$admin->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="admin_name"
                               class="form-control @error('admin_name') is-invalid @enderror"
                               placeholder="Enter name here" value="{{$admin->name}}">
                        @error('admin_name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="admin_email"
                                   class="form-control @error('admin_email') is-invalid @enderror"
                                   placeholder="Enter email here" value="{{$admin->email}}">
                            @error('admin_email')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="row my-3">
                            <div class="col-md-1">
                                <img class="img-fluid"
                                     src="{{asset('storage/images/admin/'.$admin->image)}}"
                                     width="100" height="100">
                            </div>
                            <div class="col-md mt-2">
                                <div class="custom-file">
                                    <input type="file" name="admin_image"
                                           class="custom-file-input @error('admin_image') is-invalid @enderror">
                                    <label class="custom-file-label">Choose Image</label>
                                    @error('admin_image')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Enter old password here">
                            @error('current_password')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                            @if(session('error'))
                                <div style="color: #e70000; font-size: 14px;padding: 2px;">{{session('error')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter new password here">
                            @error('password')
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
                            <input type="submit" class="btn btn-outline-success btn-block" value="Update profile">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
