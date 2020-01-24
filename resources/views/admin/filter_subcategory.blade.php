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
                        <button class="btn" data-toggle="collapse" style="background-color: #aa1dbf; color: white;" data-target="#create-product">
                            <i class="fas fa-plus-circle" style="color: white;"></i> Create A Filter Subcategory
                        </button>
                    </div>
                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_filter_subcategory')}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="category_form"
                                name="category_form">
                                @csrf
                                <div class="col-md m-0 px-1">
                                    <div class="form-group w-100">
                                        <label for="section" class="form-label">Category</label>
                                        <select id="filter-category" class="form-control @error('filter_subcategory_category') is-invalid @enderror"
                                                name="filter_subcategory_category">
                                            @if($categories->isEmpty())
                                                <option value="">No Category Available</option>
                                            @else
                                                <option value="">Select a Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->category}}">
                                                        {{\Illuminate\Support\Str::slug($category->category,' ')}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('filter_subcategory_category')
                                    <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category_title" class="form-label">Title</label>
                                    <input type="text" name="filter_subcategory_title"
                                           class="form-control @error('filter_subcategory_title') is-invalid @enderror"
                                           placeholder="Filter subcategory title..." value="{{old('filter_subcategory_title')}}">
                                    @error('filter_subcategory_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block"
                                                    style="background-color: #aa1dbf; color: white;">
                                                Create Filter Subcategory
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
                @if(!$subcategory_filters->isEmpty())
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($subcategory_filters as $subcategory)
                            <tr>
                                <td>{{$subcategory->id}}</td>
                                <td>{{$subcategory->category}}</td>
                                <td>{{$subcategory->subcategory}}</td>
                                <td><a href="{{route('admin_edit_filter_subcategory_view',$subcategory->id)}}" class="edit-text" title="edit product"><i class="far fa-edit"></i></a>
                                    &nbsp;
                                    <form id="delete_form{{$subcategory->id}}" class="d-inline" action="{{route('delete_filter_subcategory',$subcategory->id)}}" method="post">
                                        @csrf
                                        <button type="button" class="delete-text bg-transparent border-0" title="delete brand"
                                                data-toggle="modal" data-target="#confirmation_modal{{$subcategory->id}}"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Filter Subcategories To Display</div>
                @endif
            </div>
        </div>
    </div>
    {{--    delete confirmation modal--}}
    @foreach($subcategory_filters as $subcategory)
        @component('components.confirmation_modal')
            @slot('modal_id')
                {{$subcategory->id}}
            @endslot
            @slot('btn_id')
                {{$subcategory->id}}
            @endslot
        @endcomponent
    @endforeach
@endsection
