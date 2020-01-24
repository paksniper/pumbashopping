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
                        <button class="btn btn-outline-dark" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update Fashion
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_fashion',$fashion->id)}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="fashion_form"
                                name="fashion_form">
                                @csrf
                                <div class="form-group">
                                    <label for="fashion_title" class="form-label">Title</label>
                                    <input type="text" name="fashion_title"
                                           class="form-control @error('fashion_title') is-invalid @enderror"
                                           placeholder="Fashion title..." value="{{$fashion->fashion}}">
                                    @error('fashion_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
{{--                                <div class="row my-5">--}}
{{--                                    <div class="col-md-1">--}}
{{--                                        <img class="img-fluid"--}}
{{--                                             src="{{asset('storage/images/'.Str::slug($fashion->fashion,'-').'/'.$fashion->image)}}"--}}
{{--                                             width="34" height="34">--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" name="fashion_image"--}}
{{--                                                   class="custom-file-input @error('fashion_image') is-invalid @enderror">--}}
{{--                                            <label class="custom-file-label">Choose Image</label>--}}
{{--                                            @error('fashion_image')--}}
{{--                                            <div class="invalid-feedback">{{$message}}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-dark btn-block">
                                                Update Fashion
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
