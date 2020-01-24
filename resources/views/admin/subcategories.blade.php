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
                        <button class="btn btn-outline-light text-dark" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Subcategory
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_subcategory')}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="subcategory_form"
                                name="subcategory_form">
                                @csrf
                                <div class="col-md m-0 px-1">
                                    <div class="form-group w-100">
                                        <label for="section" class="form-label">Fashion</label>
                                        <select id="subcat-fashion"
                                                class="form-control @error('product_fashion') is-invalid @enderror"
                                                name="product_fashion">
                                            @if($fashions->isEmpty())
                                                <option value="">No Fashion Available</option>
                                            @else
                                                <option value="">Select Fashion</option>
                                                @foreach($fashions as $fashion)
                                                    <option value="{{$fashion->fashion}}">
                                                        {{\Illuminate\Support\Str::slug($fashion->fashion,' ')}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('product_fashion')
                                    <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md m-0 px-1">
                                    <div class="form-group w-100">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-control @error('subcategory_category') is-invalid @enderror"
                                                name="subcategory_category" id="category">
                                            @if($categories->isEmpty())
                                                <option value="">No Category Available</option>
                                            @else
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->category}}">
                                                        {{\Illuminate\Support\Str::slug($category->category,' ')}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('subcategory_category')
                                    <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="subcategory_title" class="form-label">Title</label>
                                    <input type="text" name="subcategory_title"
                                           class="form-control @error('subcategory_title') is-invalid @enderror"
                                           placeholder="Subcategory title..." value="{{old('subcategory_title')}}">
                                    @error('subcategory_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-light btn-block text-dark">
                                                Create Subcategory
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
                @if(!$subcategories->isEmpty())
                    <table id="data-table" class="table table-bordere table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Id</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($subcategories as $subcategory)
                            <tr>
                                <td>{{$subcategory->id}}</td>
                                <td>{{$subcategory->category}}</td>
                                <td>{{\Illuminate\Support\Str::slug($subcategory->subcategory,' ')}}</td>
                                <td><a href="{{route('admin_edit_subcategory_view',$subcategory->id)}}" class="edit-text" title="edit product"><i class="far fa-edit"></i></a>

                                    <form id="delete_form{{$subcategory->id}}" class="d-inline" action="{{route('delete_subcategory',$subcategory->id)}}" method="post">
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
                    <div class="text-center">No Subcategories To Display</div>
                @endif
            </div>
        </div>
    </div>

    {{--    delete confirmation modal--}}
    @foreach($subcategories as $subcategory)
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
