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
                        <button class="btn" data-toggle="collapse" style="background-color: #aa1dbf; color: white;" data-target="#create-product">
                            <i class="far fa-edit" style="color: white;"></i> Update Filter Subcategory
                        </button>
                    </div>
                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_filter_subcategory',$subcategory_filter->id)}}"
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
                                            @if(empty($subcategory_filter))
                                                <option value="">No Subcategory Available</option>
                                            @else
                                                <option value="{{$subcategory_filter->category}}">{{$subcategory_filter->category}}</option>
{{--                                                @foreach($categories as $category)--}}
{{--                                                    @if($subcategory_filter->category != $category->category)--}}
{{--                                                    <option value="{{$category->category}}">--}}
{{--                                                        {{\Illuminate\Support\Str::slug($category->category,' ')}}--}}
{{--                                                    </option>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
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
                                           placeholder="Filter subcategory title..." value="{{$subcategory_filter->subcategory}}">
                                    @error('filter_subcategory_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block"
                                                    style="background-color: #aa1dbf; color: white;">
                                                Update Filter Subcategory
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
