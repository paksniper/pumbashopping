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
                        <button class="btn btn-outline-warning" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Slider
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_slider',$slider->id)}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="slider_form"
                                name="slider_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Section</label>
                                            <select class="form-control @error('slider_section') is-invalid @enderror"
                                                    name="slider_section">
                                                <option value="{{$slider->section}}">{{$slider->section}}</option>
                                                @foreach($sections as $section)
                                                    @if($section->title === $slider->section)
                                                    @else
                                                        <option value="{{$section->title}}">
                                                            {{\Illuminate\Support\Str::slug($section->title,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('slider_section')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-1">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/sliders/'.$slider->slider_image)}}"
                                             width="100" height="100">
                                    </div>
                                    <div class="col-md">
                                        <div class="custom-file">
                                            <input type="file" name="slider_image"
                                                   class="custom-file-input @error('slider_image') is-invalid @enderror">
                                            <label class="custom-file-label">Choose Image</label>
                                            @error('slider_image')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-warning btn-block">
                                                Update Slider
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
