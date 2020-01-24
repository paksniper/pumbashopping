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
                        <button class="btn btn-outline-info" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Category
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_category',$category->id)}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="category_form"
                                name="category_form">
                                @csrf
                                <input type="hidden" value="{{$category->category}}" name="original_category">
                                <input type="hidden" value="{{$category->fashion}}" name="original_fashion">
                                <div class="col-md m-0 px-1">
                                    <div class="form-group w-100">
                                        <label for="section" class="form-label">Fashion</label>
                                        <select class="form-control @error('category_fashion') is-invalid @enderror"
                                                name="category_fashion">
                                                <option value="{{$category->fashion}}">{{$category->fashion}}</option>
                                                @foreach($fashions as $fashion)
                                                    @if($category->fashion != $fashion->fashion)
                                                    <option value="{{$fashion->fashion}}">
                                                        {{\Illuminate\Support\Str::slug($fashion->fashion,' ')}}
                                                    </option>
                                                    @endif
                                                @endforeach
                                        </select>
                                    </div>
                                    @error('category_fashion')
                                    <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category_title" class="form-label">Title</label>
                                    <input type="text" name="category_title"
                                           class="form-control @error('category_title') is-invalid @enderror"
                                           placeholder="Category title..." value="{{$category->category}}">
                                    @error('category_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-info btn-block">
                                                Update Category
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
