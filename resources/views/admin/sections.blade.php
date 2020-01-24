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
                        <button class="btn btn-outline-danger" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Section
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_section')}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="section_form"
                                name="section_form">
                                @csrf
                                <div class="form-group">
                                    <label for="section_title" class="form-label">Title</label>
                                    <input type="text" name="section_title"
                                           class="form-control @error('section_title') is-invalid @enderror"
                                           placeholder="section title..." value="{{old('section_title')}}">
                                    @error('section_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-danger btn-block">
                                                Create Section
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
                @if($sections !== "")
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td>{{$section->id}}</td>
                                <td>{{\Illuminate\Support\Str::slug($section->title,' ')}}</td>
                                <td><a href="{{route('admin_edit_section_view',$section->id)}}" class="edit-text"
                                       title="edit product"><i class="far fa-edit"></i></a>
                                    &nbsp;
                                    <form id="delete_form{{$section->id}}" class="d-inline"
                                          action="{{route('delete_section',$section->id)}}"
                                          method="post">
                                        @csrf
                                        <button type="button" class="delete-text bg-transparent border-0"
                                                data-toggle="modal" data-target="#confirmation_modal{{$section->id}}"
                                                title="delete section"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Sections To Display</div>
                @endif
            </div>
        </div>
    </div>

    {{--    delete confirmation modal--}}
    @foreach($sections as $section)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$section->id}}
            @endslot
            @slot('btn_id')
                {{$section->id}}
            @endslot
        @endcomponent
    @endforeach
@endsection
