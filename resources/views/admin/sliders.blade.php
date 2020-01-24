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
                        <button class="btn btn-outline-warning" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Slider
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_slider')}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="slider_form"
                                name="slider_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md m-0">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Section</label>
                                            <select class="form-control @error('slider_section') is-invalid @enderror"
                                                    name="slider_section">
                                                @if($sections->isEmpty())
                                                    <option value="">No Section Available</option>
                                                @else
                                                    <option value="">Select a Section</option>
                                                    @foreach($sections as $section)
                                                        <option value="{{$section->title}}">
                                                            {{$section->title}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('slider_section')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="custom-file mb-4">
                                    <input type="file" name="slider_image"
                                           class="custom-file-input @error('slider_image') is-invalid @enderror">
                                    <label class="custom-file-label">Choose Image</label>
                                    @error('slider_image')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-warning btn-block">
                                                Create Slider
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
                @if(!$sliders->isEmpty())
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Section</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>{{$slider->id}}</td>
                                <td>
                                    <a href=""
                                       class="table-data">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/sliders/'.$slider->slider_image)}}"
                                             width="50" height="50">
                                    </a>

                                </td>
                                <td>{{\Illuminate\Support\Str::slug($slider->section,' ')}}</td>
                                <td><a href="{{route('admin_edit_slider_view',$slider->id)}}" class="edit-text" title="edit slider"><i class="far fa-edit"></i></a>
                                    &nbsp;
                                    <form id="delete_form{{$slider->id}}" class="d-inline" action="{{route('delete_slider',$slider->id)}}" method="post"
                                          data-toggle="modal" data-target="#confirmation_modal{{$slider->id}}">
                                        @csrf
                                        <button type="button" class="delete-text bg-transparent border-0" title="delete slider"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Sliders To Display</div>
                @endif
            </div>
        </div>
    </div>

    {{--    delete confirmation modal--}}
    @foreach($sliders as $slider)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$slider->id}}
            @endslot
            @slot('btn_id')
                {{$slider->id}}
            @endslot
        @endcomponent
    @endforeach

@endsection
