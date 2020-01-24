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
                            <i class="fas fa-plus-circle" style="color: white;"></i> Create A Filter Category
                        </button>
                    </div>
                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_filter_category')}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="category_form"
                                name="category_form">
                                @csrf
{{--                                <div class="col-md m-0 px-1">--}}
{{--                                    <div class="form-group w-100">--}}
{{--                                        <label for="section" class="form-label">Fashion</label>--}}
{{--                                        <select class="form-control @error('filter_fashion') is-invalid @enderror"--}}
{{--                                                name="filter_fashion">--}}
{{--                                            @if($fashions->isEmpty())--}}
{{--                                                <option value="">No Fashion Available</option>--}}
{{--                                            @else--}}
{{--                                                <option value="">Select a Fashion</option>--}}
{{--                                                @foreach($fashions as $fashion)--}}
{{--                                                    <option value="{{$fashion->fashion}}">--}}
{{--                                                        {{\Illuminate\Support\Str::slug($fashion->fashion,' ')}}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    @error('filter_fashion')--}}
{{--                                    <span class="text-danger" style="font-size: 15px;">{{$message}}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label for="category_title" class="form-label">Filter Category</label>
                                    <input type="text" name="filter_title"
                                           class="form-control @error('filter_title') is-invalid @enderror"
                                           placeholder="Filter title..." value="{{old('filter_title')}}">
                                    @error('filter_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block"
                                                    style="background-color: #a33807; color: white;">
                                                Create Filter Category
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
                @if(!$filters->isEmpty())
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Category</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($filters as $filter)
                            <tr>
                                <td>{{$filter->id}}</td>
                                <td>{{$filter->category}}</td>
                                <td><a href="{{route('admin_edit_filter_category_view',$filter->id)}}" class="edit-text" title="edit product"><i class="far fa-edit"></i></a>
                                    &nbsp;
                                    <form id="delete_form{{$filter->id}}" class="d-inline" action="{{route('delete_filter_category',$filter->id)}}" method="post">
                                        @csrf
                                        <button type="button" class="delete-text bg-transparent border-0" title="delete brand"
                                                data-toggle="modal" data-target="#confirmation_modal{{$filter->id}}"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Filter Categories To Display</div>
                @endif
            </div>
        </div>
    </div>
    {{--    delete confirmation modal--}}
    @foreach($filters as $filter)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$filter->id}}
            @endslot
            @slot('btn_id')
                {{$filter->id}}
            @endslot
        @endcomponent
    @endforeach
@endsection
