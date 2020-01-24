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
                        <button class="btn btn-outline-dark" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Fashion
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_fashion')}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="fashion_form"
                                name="fashion_form">
                                @csrf
                                <div class="form-group">
                                    <label for="fashion_title" class="form-label">Title</label>
                                    <input type="text" name="fashion_title"
                                           class="form-control @error('fashion_title') is-invalid @enderror"
                                           placeholder="Fashion title..." value="{{old('fashion_title')}}">
                                    @error('fashion_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
{{--                                <div class="custom-file mb-4">--}}
{{--                                    <input type="file" name="fashion_image"--}}
{{--                                           class="custom-file-input @error('fashion_image') is-invalid @enderror">--}}
{{--                                    <label class="custom-file-label">Choose Image</label>--}}
{{--                                    @error('fashion_image')--}}
{{--                                    <div class="invalid-feedback">{{$message}}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-dark btn-block">
                                                Create Fashion
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
                @if(!$fashions->isEmpty())
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
{{--                        <th>Image</th>--}}
                        <th>Fashion</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($fashions as $fashion)
                            <tr>
                                <td>{{$fashion->id}}</td>
{{--                                <td>--}}
{{--                                    <a href=""--}}
{{--                                       class="table-data">--}}
{{--                                        <img class="img-fluid"--}}
{{--                                             src="{{asset('storage/images/'.$fashion->fashion.'/'.$fashion->image)}}">--}}
{{--                                    </a>--}}

{{--                                </td>--}}
                                <td>{{\Illuminate\Support\Str::slug($fashion->fashion,' ')}}</td>
                                <td><a href="{{route('admin_edit_fashion_view',$fashion->id)}}" class="edit-text" title="edit product"><i class="far fa-edit"></i></a>

                                    <form id="delete_form{{$fashion->id}}" class="d-inline" action="{{route('delete_fashion',$fashion->id)}}" method="post">
                                        @csrf
                                        <button type="button" class="delete-text bg-transparent border-0" title="delete brand"
                                                data-toggle="modal" data-target="#confirmation_modal{{$fashion->id}}"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Fashions To Display</div>
                @endif
            </div>
        </div>
    </div>

    {{--    delete confirmation modal--}}
    @foreach($fashions as $fashion)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$fashion->id}}
            @endslot
            @slot('btn_id')
                {{$fashion->id}}
            @endslot
        @endcomponent
    @endforeach

@endsection
