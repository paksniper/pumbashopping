@extends('layout.app')

@section('title')
    Admin Registration
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
                            <h5 class="text-center">Registration</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{route('post_admin_registration')}}"
                            method="post" enctype="multipart/form-data"
                              name="admin_registration_form"
                              id="admin_registration_form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="admin_name"
                                       class="form-control @error('admin_name') is-invalid @enderror"
                                       placeholder="Enter name here" value="{{old('admin_name')}}">
                                @error('admin_name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="admin_email"
                                       class="form-control @error('admin_email') is-invalid @enderror"
                                       placeholder="Enter email here" value="{{old('admin_email')}}">
                                @error('admin_email')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <label>Profile Picture</label>
                            <div class="custom-file mb-3">
                                <input type="file" name="admin_image"
                                       class="custom-file-input @error('admin_image') is-invalid @enderror">
                                <label class="custom-file-label">Choose Image</label>
                                @error('admin_image')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="admin_password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Enter password here">
                                @error('password')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input type="password" name="confirm_password"
                                       class="form-control @error('confirm_password') is-invalid @enderror"
                                       placeholder="Enter password here">
                                @error('confirm_password')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-outline-success btn-block" value="Register">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
