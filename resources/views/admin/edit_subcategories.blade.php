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
                        <button class="btn btn-outline-light text-dark" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Subcategory
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_subcategory',$subcategory->id)}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="subcategory_form"
                                name="subcategory_form">
                                @csrf
                                <input type="hidden" name="original_subcategory" value="{{$subcategory->subcategory}}">
                                <div class="col-md m-0 px-1">
                                    <div class="form-group w-100">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-control @error('subcategory_category') is-invalid @enderror"
                                                name="subcategory_category">
                                            <option value="{{$subcategory->category}}">{{$subcategory->category}}</option>
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
                                           placeholder="Subcategory title..." value="{{$subcategory->subcategory}}">
                                    @error('subcategory_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-light btn-block text-dark">
                                                Update Subcategory
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
