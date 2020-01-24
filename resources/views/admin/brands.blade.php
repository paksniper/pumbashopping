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
                        <button class="btn btn-outline-success" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Brand
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_brand')}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="brand_form"
                                name="brand_form">
                                @csrf
                                <div class="form-group">
                                    <label for="brand_title" class="form-label">Title</label>
                                    <input type="text" name="brand_title"
                                           class="form-control @error('brand_title') is-invalid @enderror"
                                           placeholder="brand title..." value="{{old('brand_title')}}">
                                    @error('brand_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="custom-file mb-4">
                                    <input type="file" name="brand_image"
                                           class="custom-file-input @error('brand_image') is-invalid @enderror">
                                    <label class="custom-file-label">Choose Image</label>
                                    @error('brand_image')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-success btn-block">
                                                Create Brand
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

    <div class="container mt-4">
        <div class="row px-auto">
            <div class="col-md">
                @if($brands !== "")
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td>{{$brand->id}}</td>
                                <td>
                                    <a href=""
                                       class="table-data">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/brands/'.$brand->image)}}"
                                             width="50" height="50">
                                    </a>

                                </td>
                                <td>{{\Illuminate\Support\Str::slug($brand->title,' ')}}</td>
                                <td><a href="{{route('admin_edit_brand_view',$brand->id)}}" class="edit-text" title="edit brand"><i class="far fa-edit"></i></a>
                                    &nbsp;
                                    <form id="delete_form{{$brand->id}}" class="d-inline" action="{{route('delete_brand',$brand->id)}}" method="post">
                                        @csrf
                                    <button type="button" class="delete-text bg-transparent border-0" title="delete brand"
                                            data-toggle="modal" data-target="#confirmation_modal{{$brand->id}}"><i
                                            class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Brands To Display</div>
                @endif
            </div>
        </div>
    </div>

    {{--    delete confirmation modal--}}
    @foreach($brands as $brand)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$brand->id}}
            @endslot
            @slot('btn_id')
                {{$brand->id}}
            @endslot
        @endcomponent
    @endforeach
@endsection
