@extends('layout.admin_app')

@section('title')
    PumbaShopping
@endsection
@section('content')

    <div class="container mt-5">
        <div class="row">
            <div id="accordion" class="w-100">
                <div class="card">
                    <div class="card-footer bg-white border-bottom border-top-0">
                        <button class="btn btn-outline-success" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Brand
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_brand',$brand->id)}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="brand_form"
                                name="brand_form">
                                @csrf
                                <input type="hidden" value="{{$brand->title}}" name="original_title">
                                <div class="form-group">
                                    <label for="brand_title" class="form-label">Title</label>
                                    <input type="text" name="brand_title"
                                           class="form-control @error('brand_title') is-invalid @enderror"
                                           placeholder="brand title..." value="{{$brand->title}}">
                                    @error('brand_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/brands/'.$brand->image)}}"
                                             width="100" height="100">
                                    </div>
                                    <div class="col-md">
                                        <div class="custom-file">
                                            <input type="file" name="brand_image"
                                                   class="custom-file-input @error('brand_image') is-invalid @enderror">
                                            <label class="custom-file-label">Choose Image</label>
                                            @error('brand_image')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-success btn-block">
                                                Update Brand
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
