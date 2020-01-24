@extends('layout.admin_app')

@section('title')
    PumbaShopping
@endsection
@section('content')
        <div class="row mt-5 px-5">
            <div class="col-md text-center px-1">
                <div class="card bg-primary">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Products</div>
                        <div class="d-flex justify-content-center">
                            <i class="fab fa-product-hunt fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($product_count))
                                    &nbsp;{{$product_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="#" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-success">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Brands</div>
                        <div class="d-flex justify-content-center">
                            <i class="fab fa-blogger-b fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($brand_count))
                                    &nbsp;{{$brand_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_brand_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-warning">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Sliders</div>
                        <div class="d-flex justify-content-center">
                            <i class="fa fa-images fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($slider_count))
                                    &nbsp;{{$slider_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_slider_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-danger">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Sections</div>
                        <div class="d-flex justify-content-center">
                            <i class="fa fa-chart-pie fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($section_count))
                                    &nbsp;{{$section_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_section_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-info">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Categories</div>
                        <div class="d-flex justify-content-center">
                            <i class="fa fa-cubes fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($category_count))
                                    &nbsp;{{$category_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_category_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-light">
                    <div class="card-body p-1">
                        <div class="card-title text-dark">Subcategories</div>
                        <div class="d-flex justify-content-center">
                            <i class="fas fa-network-wired fa-3x"></i>
                            <h1 class="mt-2 d-inline-block text-dark">
                                @if(!empty($category_count))
                                    &nbsp;{{$category_count}}
                                @else
                                    &nbsp;0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_subcategory_view')}}" class="mt-2 btn btn-outline-dark">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1">
                <div class="card bg-dark">
                    <div class="card-body p-1">
                        <div class="card-title text-white">Fashions</div>
                        <div class="d-flex justify-content-center">
                            <i class="fas fa-icons fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($fashion_count))
                                    &nbsp;{{$fashion_count}}
                                @else
                                    0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_fashion_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1" >
                <div class="card bg-dark">
                    <div class="card-body p-1" style="background-color: #a33807;">
                        <div class="card-title text-white">Filter</div>
                        <div class="d-flex justify-content-center">
                            <i class="fas fa-filter fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($fashion_count))
                                    &nbsp;{{$fashion_count}}
                                @else
                                    0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_fashion_view')}}" class="mt-2 btn btn-outline-light">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md text-center px-1" >
                <div class="card bg-dark">
                    <div class="card-body p-1" style="background-color: #aa1dbf;">
                        <div class="card-title text-white">Filter Sub</div>
                        <div class="d-flex justify-content-center">
                            <i class="fas fa-network-wired fa-3x text-white"></i>
                            <h1 class="mt-2 d-inline-block text-white">
                                @if(!empty($fashion_count))
                                    &nbsp;{{$fashion_count}}
                                @else
                                    0
                                @endif
                            </h1>
                        </div>
                        <a href="{{route('admin_fashion_view')}}" class="mt-2 btn btn-outline-light">View</a>
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
