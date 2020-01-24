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
                        <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Product
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_product',$product->id)}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="product_form"
                                name="product_form">
                                <div class="row">
                                    @csrf
{{--                                    <input type="hidden" name="original_fashion" value="{{$product->fashion}}">--}}
{{--                                    <input type="hidden" name="original_category" value="{{$product->category}}">--}}
{{--                                    <input type="hidden" name="original_subcategory" value="{{$product->subcategory}}">--}}
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Section</label>
                                            <select class="form-control @error('product_section') is-invalid @enderror"
                                                    name="product_section">
                                                    <option value="{{$product->section}}">{{$product->section}}</option>
                                                    @foreach($sections as $section)
                                                        @if($section->title === $product->section)
                                                            @else
                                                        <option value="{{$section->title}}">
                                                            {{\Illuminate\Support\Str::slug($section->title,' ')}}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                        @error('product_section')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Fashion</label>
                                            <select id="fashion" class="form-control @error('product_fashion') is-invalid @enderror"
                                                    name="product_fashion">
                                                <option value="{{$product->fashion}}">{{$product->fashion}}</option>
                                                @foreach($fashions as $fashion)
                                                    @if($fashion->fashion === $product->fashion)
                                                    @else
                                                        <option value="{{$fashion->fashion}}">
                                                            {{\Illuminate\Support\Str::slug($fashion->fashion,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('product_fashion')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Category</label>
                                            <select id="category" class="form-control @error('product_category') is-invalid @enderror"
                                                    name="product_category">
                                                <option value="{{$product->category}}">{{$product->category}}</option>
                                                @foreach($categories as $category)
                                                    @if($category->category === $product->category)
                                                    @else
                                                        <option value="{{$category->category}}">
                                                            {{\Illuminate\Support\Str::slug($category->category,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('product_category')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">SubCategory</label>
                                            <select id="subcategory" class="form-control @error('product_subcategory') is-invalid @enderror"
                                                    name="product_subcategory">
                                                <option value="{{$product->subcategory}}">{{$product->subcategory}}</option>
                                                @foreach($subcategories as $subcategory)
                                                    @if($subcategory->subcategory === $product->subcategory)
                                                    @else
                                                        <option value="{{$subcategory->subcategory}}">
                                                            {{\Illuminate\Support\Str::slug($subcategory->subcategory,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('product_subcategory')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Brand</label>
                                            <select class="form-control @error('product_brand') is-invalid @enderror"
                                                    name="product_brand">
                                                <option value="{{$product->brand}}">{{$product->brand}}</option>
                                                @foreach($brands as $brand)
                                                    @if($brand->title === $product->brand)
                                                    @else
                                                        <option value="{{$brand->title}}">
                                                            {{\Illuminate\Support\Str::slug($brand->title,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('product_brand')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Trader</label>
                                            <select class="form-control @error('product_trader') is-invalid @enderror"
                                                    name="product_trader">
                                                <option value="{{$product->trader}}">{{$product->trader}}</option>
                                                @foreach($traders as $trader)
                                                    @if($trader->title === $product->trader)
                                                    @else
                                                        <option value="{{$trader->title}}">
                                                            {{\Illuminate\Support\Str::slug($trader->title,' ')}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('product_trader')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div id="filter-selection-container" class="row">
                                    <div id="filter" class="col-md-2 m-0 px-1">
                                        <div  class="form-group w-100">
                                            <label for="section" class="form-label">Select Filter</label>
                                            <select id="filter-selection" class="form-control @error('product_filter') is-invalid @enderror"
                                                    name="product_filter">
                                                @if($filter_categories->isEmpty())
                                                    <option value="">No Filter Available</option>
                                                @else
                                                    <option value="">Select Filter</option>
                                                    <option value="reset-filter">Reset Filter</option>
                                                    @foreach($filter_categories as $filter)
                                                        <option value="{{$filter->category}}">
                                                            {{\Illuminate\Support\Str::slug($filter->category,' ')}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('product_filter')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    {!! $filter_html !!}
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Title</label>
                                    <input type="text" name="product_title"
                                           class="form-control @error('product_title') is-invalid @enderror"
                                           placeholder="product title..." value="{{$product->title}}">
                                    @error('product_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Discount Price</label>
                                    <input type="number" name="product_discount_price"
                                           class="form-control @error('product_discount_price') is-invalid @enderror"
                                           placeholder="discount price..." value="{{$product->discount}}">
                                    @error('product_discount_price')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Price</label>
                                    <input type="number" name="product_price"
                                           class="form-control @error('product_price') is-invalid @enderror"
                                           placeholder="product price..." value="{{$product->price}}">
                                    @error('product_price')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/'.$product->image)}}"
                                             width="100" height="100">
                                    </div>
                                    <div class="col-md mt-3">
                                        <div class="custom-file">
                                            <input type="file" name="product_image"
                                                   class="custom-file-input @error('product_image') is-invalid @enderror">
                                            <label class="custom-file-label">Choose Image</label>
                                            @error('product_image')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="product_title" class="form-label">Specifications</label>
                                        <textarea name="product_specification"
                                                  class="@error('product_specification') is-invalid @enderror">
                                            {{$product->specification}}
                                        </textarea>
                                        @error('product_specification')
                                        <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="product_title" class="form-label">Features</label>
                                        <textarea name="product_feature"
                                                  class="@error('product_feature') is-invalid @enderror">
                                            {{$product->feature}}
                                        </textarea>
                                        @error('product_feature')
                                        <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="product_title" class="form-label">Description</label>
                                    <textarea name="product_description"
                                              class="@error('product_description') is-invalid @enderror">
                                        {{$product->description}}
                                    </textarea>
                                    @error('product_description')
                                    <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-primary btn-block">
                                                Make An Update
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
