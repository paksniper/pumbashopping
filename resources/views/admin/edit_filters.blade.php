@extends('layout.admin_app')

@section('title')
    PumbaShopping
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

    <div class="container mt-5">
        <div class="row">
            <div id="accordion" class="w-100">
                <div class="card">
                    <div class="card-footer bg-white border-bottom border-top-0">
                        <button class="btn" data-toggle="collapse" style="background-color: #a33807; color: white;" data-target="#create-product">
                            <i class="far fa-edit" style="color: white;"></i> Update Filter Category
                        </button>
                    </div>
                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_filter_category',$filtercategory->id)}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="category_form"
                                name="category_form">
                                @csrf
                                <div class="form-group">
                                    <label for="category_title" class="form-label">Filter Category</label>
                                    <input type="text" name="filter_title"
                                           class="form-control @error('filter_title') is-invalid @enderror"
                                           placeholder="Filter title..." value="{{$filtercategory->category}}">
                                    @error('filter_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block"
                                                    style="background-color: #a33807; color: white;">
                                                Update Filter Category
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
