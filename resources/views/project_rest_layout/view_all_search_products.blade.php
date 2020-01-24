@extends(\Illuminate\Support\Facades\Auth::guard('admin')->check() ?'layout.admin_app':'layout.app')

@section('content')
    <!-- Displaying All Products Start Here -->

    <section id="product-detail" class="my-5">
        <div class="container p-0">
            <div class="row">
                <div class="col-md-3 border p-0">
                    <div class="p-2 border-bottom-0">
                        <h6 class="font-weight-bold">FILTERS</h6>
                    </div>
                    <div class="card mb-3 border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-footer border-bottom p-2 bg-white categories-btn">
                            <div class="card-text float-left">Categories</div>
                            <span class="float-right fas fa-angle-up cat-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body categories-container p-1">
                            <ul class="list-unstyled categories mCustomScrollbar" data-mcs-theme="dark">
                                @foreach($subcategories as $subcat)
                                    <a href="#" class="subcategory-item-container">
                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <img class="img-fluid" src="{{asset('storage/images/subcategories/bra.png')}}" >
                                            <span class="ml-3">{{\Illuminate\Support\Str::slug($subcat->subcategory," ")}}</span>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-footer border-bottom p-2 bg-white brands-btn">
                            <div class="card-text float-left">Brands</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body brands-container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" data-mcs-theme="dark">
                                @foreach($brands as $brand)
                                    <a href="#" class="subcategory-item-container">
                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <img class="img-fluid" src="{{asset('storage/images/brands/brand.png')}}" >
                                            <span class="ml-3">{{\Illuminate\Support\Str::slug($brand->title," ")}}</span>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            Search Result({{$count}} Products Found)
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                @if($searchproducts !== "")
                                    @foreach($searchproducts as $product)
                                        <div class="col-md-3 px-1 product-on-view">
                                            @component('components.product_card')
                                                @slot('product_img_url')
                                                    {{route('product_detail',\Illuminate\Support\Str::slug($product->title ,'-'))}}
                                                    @slot('image_source')
                                                        {{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/'.$product->image)}}
                                                    @endslot
                                                @endslot

                                                @slot('product_title_url')
                                                    {{route('product_detail',$product->image )}}
                                                    @slot('product_title')
                                                        {{$product->title}}
                                                    @endslot
                                                @endslot

                                                    @slot('discount_price')
                                                        @if($product->discount != "")
                                                            <span class="text-success">Rs.&nbsp{{$product->discount}}</span>
                                                        @endif
                                                    @endslot

                                                    @slot('original_price')
                                                        @if($product->discount != "")
                                                            &nbsp<s class="text-muted"><small>Rs.&nbsp{{$product->price}}</small></s>
                                                        @else
                                                            &nbsp<span class="text-success">Rs.&nbsp{{$product->price}}</span>
                                                        @endif

                                                    @endslot

                                                    @slot('discount_percentage')
                                                        @if($product->discount != "")
                                                            &nbsp;<span class="text-danger">{{$product->percentage}}% Off</span>
                                                        @endif
                                                    @endslot
                                            @endcomponent
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- Pagination starts here -->
                    <div class="row mt-5">
                        <div class="col showing-page-tag">
                            @if(!empty($count))
                                Showing Page {{$searchproducts->currentPage()}} of {{$searchproducts->lastPage()}}
                            @endif
                        </div>
                        <div class="col d-flex justify-content-end">
                            @if(!empty($count))
                                {{$searchproducts->links()}}
                            @endif
                        </div>

                    </div>

                {{--                    <nav class="d-flex justify-content-end">--}}
                {{--                        <ul class="pagination pagination-sm">--}}
                {{--                            <li class="page-item">--}}
                {{--                                <a href="#" class="page-link">--}}
                {{--                                    <span class="fas fa-angle-double-left"></span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li class="page-item active"><a href="#" class="page-link">1</a></li>--}}
                {{--                            <li class="page-item"><a href="#" class="page-link">2</a></li>--}}
                {{--                            <li class="page-item"><a href="#" class="page-link">3</a></li>--}}
                {{--                            <li class="page-item">--}}
                {{--                                <a href="#" class="page-link">--}}
                {{--                                    <span class="fas fa-angle-double-right"></span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                        </ul>--}}
                {{--                    </nav>--}}
                <!-- Pagination ends here -->
                </div>
            </div>
        </div>
    </section>
    <!-- Product Detail Ends Here -->

@endsection
