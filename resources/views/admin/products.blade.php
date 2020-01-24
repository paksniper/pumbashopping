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
                        <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#create-product">
                            <i class="fas fa-plus-circle"></i> Create A Product
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('create_product')}}"
                                method="post"
                                enctype="multipart/form-data"
                                id="product_form"
                                name="product_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Section</label>
                                            <select class="form-control @error('product_section') is-invalid @enderror"
                                                    name="product_section">
                                                @if($sections->isEmpty())
                                                    <option value="">No Section Available</option>
                                                @else
                                                    <option value="">Select Section</option>
                                                    @foreach($sections as $section)
                                                        <option value="{{$section->title}}">
                                                            {{\Illuminate\Support\Str::slug($section->title,' ')}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('product_section')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">Fashion</label>
                                            <select id="fashion"
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
                                            <label for="section" class="form-label">Category</label>
                                            <select id="category"
                                                    class="form-control @error('product_category') is-invalid @enderror"
                                                    name="product_category">

                                                {{--                                                @if($categories->isEmpty())--}}
                                                {{--                                                    <option value="">No Category Available</option>--}}
                                                {{--                                                @else--}}
                                                {{--                                                    <option value="">Select Category</option>--}}
                                                {{--                                                    @foreach($categories as $category)--}}
                                                {{--                                                        <option value="{{$category->category}}">--}}
                                                {{--                                                            {{\Illuminate\Support\Str::slug($category->category,' ')}}--}}
                                                {{--                                                        </option>--}}
                                                {{--                                                    @endforeach--}}
                                                {{--                                                @endif--}}
                                            </select>
                                        </div>
                                        @error('product_category')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md m-0 px-1">
                                        <div class="form-group w-100">
                                            <label for="section" class="form-label">SubCategory</label>
                                            <select id="subcategory"
                                                    class="form-control @error('product_subcategory') is-invalid @enderror"
                                                    name="product_subcategory">
                                                {{--                                                @if($subcategories->isEmpty())--}}
                                                {{--                                                    <option value="">No Subcategory Available</option>--}}
                                                {{--                                                @else--}}
                                                {{--                                                    <option value="">Select Subcategory</option>--}}
                                                {{--                                                    @foreach($subcategories as $subcategory)--}}
                                                {{--                                                        <option value="{{$subcategory->subcategory}}">--}}
                                                {{--                                                            {{\Illuminate\Support\Str::slug($subcategory->subcategory,' ')}}--}}
                                                {{--                                                        </option>--}}
                                                {{--                                                    @endforeach--}}
                                                {{--                                                @endif--}}
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
                                                @if($brands->isEmpty())
                                                    <option value="">No Brand Available</option>
                                                @else
                                                    <option value="">Select Brand</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{$brand->title}}">
                                                            {{\Illuminate\Support\Str::slug($brand->title,' ')}}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                @if($traders->isEmpty())
                                                    <option value="">No Trader Available</option>
                                                @else
                                                    <option value="">Select Trader</option>
                                                    @foreach($traders as $trader)
                                                        <option value="{{$trader->title}}">
                                                            {{\Illuminate\Support\Str::slug($trader->title,' ')}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('product_trader')
                                        <span class="text-danger" style="font-size: 15px;">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div id="filter-selection-container" class="row">
                                    <div id="selection-wrapper" class="col-md-2 m-0 px-1">
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
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Title</label>
                                    <input type="text" name="product_title"
                                           class="form-control @error('product_title') is-invalid @enderror"
                                           placeholder="product title..." value="{{old('product_title')}}">
                                    @error('product_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Discount Price</label>
                                    <input type="number" name="product_discount_price"
                                           class="form-control @error('product_discount_price') is-invalid @enderror"
                                           placeholder="discount price..." value="{{old('product_discount_price')}}">
                                    @error('product_discount_price')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_title" class="form-label">Price</label>
                                    <input type="number" name="product_price"
                                           class="form-control @error('product_price') is-invalid @enderror"
                                           placeholder="product price..." value="{{old('product_price')}}">
                                    @error('product_price')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="product_image"
                                           class="custom-file-input @error('product_image') is-invalid @enderror">
                                    <label class="custom-file-label">Choose Image</label>
                                    @error('product_image')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="product_title" class="form-label">Specifications</label>
                                        <textarea name="product_specification"
                                                  class="@error('product_specification') is-invalid @enderror">{{old('product_specification')}}
                                        </textarea>
                                        @error('product_specification')
                                        <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="product_title" class="form-label">Features</label>
                                        <textarea name="product_feature"
                                                  class="@error('product_feature') is-invalid @enderror">{{old('product_feature')}}</textarea>
                                        @error('product_feature')
                                        <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="product_title" class="form-label">Description</label>
                                    <textarea name="product_description"
                                              class="@error('product_description') is-invalid @enderror">{{old('product_description')}}</textarea>
                                    @error('product_description')
                                    <div class="text-danger" style="font-size: 15px;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-primary btn-block">
                                                Create Post
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
        <div class="row">
            <div class="col-md">
                @if($products !== "")
                    <table id="data-table" class="table table-hover table-stripe w-100"
                           style="font-size: 16px; width: 100%;">
                        <thead>
                        <th>Image</th>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Section</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Fashion</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Brand</th>
                        <th>Trader</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <a href="{{route('product_detail',\Illuminate\Support\Str::slug($product->title ,'-'))}}"
                                       class="table-data">
                                        <img class="img-fluid"
                                             src="{{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/'.$product->image)}}"
                                             width="50" height="50">
                                    </a>
                                </td>
                                <td>{{$product->id}}</td>
                                <td>{{\Illuminate\Support\Str::slug($product->title,' ')}}</td>
                                <td>
                                    <a href="{{route('view_section_products',\Illuminate\Support\Str::slug($product->section ,'-'))}}"
                                       class="table-data">
                                        {{\Illuminate\Support\Str::slug($product->section,' ')}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('view_category_products',\Illuminate\Support\Str::slug($product->category ,'-'))}}"
                                       class="table-data">
                                        {{\Illuminate\Support\Str::slug($product->category,' ')}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('view_subcategory_products',['subcategory'=>\Illuminate\Support\Str::slug($product->subcategory ,'-'),
                                    'category'=>\Illuminate\Support\Str::slug($product->category,'-')])}}"
                                       class="table-data">
                                        {{\Illuminate\Support\Str::slug($product->subcategory,' ')}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('view_fashion_products',\Illuminate\Support\Str::slug($product->fashion ,'-'))}}"
                                       class="table-data">
                                        {{\Illuminate\Support\Str::slug($product->fashion,' ')}}
                                    </a>
                                </td>
                                <td>{{$product->price}}</td>
                                <td>{{$product->discount}}</td>
                                <td>
                                    <a href="{{route('view_brand_products',\Illuminate\Support\Str::slug($product->brand ,'-'))}}"
                                       class="table-data">
                                        {{$product->brand}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('view_trader_products',\Illuminate\Support\Str::slug($product->trader ,'-'))}}"
                                       class="table-data">
                                        {{$product->trader}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('admin_edit_product_view',$product->id)}}" class="edit-text"
                                       title="edit product">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <form class="d-inline" action="{{route('delete_product',$product->id)}}"
                                          method="post">
                                        @csrf
                                        <button class="delete-text bg-transparent border-0" title="delete product"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center">No Products To Display</div>
                @endif
            </div>
        </div>
    </div>

@endsection
