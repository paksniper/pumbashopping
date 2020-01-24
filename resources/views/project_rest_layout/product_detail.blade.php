@extends(\Illuminate\Support\Facades\Auth::guard('admin')->check() ?'layout.admin_app':'layout.app')

@section('title')
    {{$product->title}}
@endsection

@section('content')
    <!-- Product Detail Starts Here -->
    <section id="product-detail" class="my-5">
        <div class="container p-0">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card zoom-card">
                                        <div id="zoom-on-img"
                                             class="card-body p-0">
                                            <img
                                                src="{{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/resize_'.$product->image)}}"
                                                data-src="{{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/'.$product->image)}}"
                                                class="img-fluid zoomimg">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h3 class="product-title text-truncate">{{$product->title}}</h3>
                                            <h3 class="d-inline"><span
                                                    class="text-success product-discount-price">
                                                    @if($product->discount != "")
                                                        Rs.&nbsp;{{$product->discount}}
                                                    @else
                                                        Rs. {{$product->price}}
                                                    @endif
                                                </span>
                                            </h3>&nbsp;<s><small
                                                    class="text-muted product-price">
                                                    @if($product->discount != "")
                                                        Rs.&nbsp;{{$product->price}}
                                                    @endif
                                                </small></s>&nbsp;
                                            <small
                                                class="text-danger product-percentage">
                                                @if($product->discount != "")
                                                    {{$product->percentage}}%&nbsp;Off
                                                @endif
                                            </small><br>
                                        </div>
                                        <div class="col p-0 product-brand mr-2">
                                            <a href="{{route('view_brand_products',\Illuminate\Support\Str::slug($brand->title,'-'))}}"
                                               class="d-flex align-items-center h-100"><img
                                                    src="{{asset('storage/images/brands/'.$brand->image)}}"
                                                    title="{{$brand->image}}" class="img-fluid"></a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="key-feature">
                                                <h5>Key Features</h5>
                                                {!! $product->feature !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sold-by">
                                                <h5>Sold By</h5>
                                                <a href="{{route('view_trader_products',$product->trader)}}"
                                                   class="text-success trader">{{$product->trader}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-3">
                                <div class="card-header p-0 bg-white">
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item border-0">
                                            <a href="#description" data-toggle="tab" class="nav-link">Description</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#return-policy" data-toggle="tab" class="nav-link">Return
                                                Policy</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#specification" data-toggle="tab"
                                               class="nav-link">Specifications</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" role="tabpanel" id="description">
                                            {!! $product->description !!}
                                        </div>
                                        <div class="tab-pane fade" role="tabpanel" id="return-policy">
                                            This is return policy
                                        </div>
                                        <div class="tab-pane fade" role="tabpanel" id="specification">
                                            {!! $product->specification !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Product You May Like Starts Here -->
                            @if($product_you_may_like->isNotEmpty())
                                @component('components.rest_card_section')
                                    @slot('carousel_container_style')
                                        box-shadow:none;
                                    @endslot
                                    @slot('section_title')
                                        {{$product_you_may_like_title}}
                                    @endslot

                                    @slot('section_carousel_id')
                                        {{$product_you_may_like_title}}

                                    @endslot
                                    @slot('product_container')
                                        @foreach($product_you_may_like as $product )
                                            <div id="card-inner-container" class="owl-item">
                                                @component('components.product_card')
                                                    @slot('product_img_url')
                                                        {{route('product_detail',\Illuminate\Support\Str::slug($product->title ,'-'))}}
                                                        @slot('image_source')
                                                            {{asset('storage/images/'.Str::slug($product->fashion,'-').'/'.Str::slug($product->category,'-').'/'.Str::slug($product->subcategory,'-').'/resize_'.$product->image)}}
                                                        @endslot
                                                    @endslot

                                                    @slot('product_title_url')
                                                        {{route('product_detail',\Illuminate\Support\Str::slug($product->title ,'-'))}}
                                                        @slot('product_title')
                                                            {{$product->title}}
                                                        @endslot
                                                    @endslot

                                                    @slot('discount_price')
                                                        @if($product->discount != "")
                                                            <span
                                                                class="text-success">Rs.&nbsp{{$product->discount}}</span>
                                                        @endif
                                                    @endslot

                                                    @slot('original_price')
                                                        @if($product->discount != "")
                                                            &nbsp<s
                                                                class="text-muted"><small>Rs.&nbsp{{$product->price}}</small></s>
                                                        @else
                                                            &nbsp<span
                                                                class="text-success">Rs.&nbsp{{$product->price}}</span>
                                                        @endif

                                                    @endslot

                                                    @slot('discount_percentage')
                                                        @if($product->discount != "")
                                                            &nbsp;<span
                                                                class="text-danger">{{$product->percentage}}% Off</span>
                                                        @endif
                                                    @endslot
                                                @endcomponent
                                            </div>
                                        @endforeach
                                    @endslot
                                    @slot('section_carousel_id')
                                        {{\Illuminate\Support\Str::slug($product_you_may_like_title,'-')}}
                                    @endslot
                                    @slot('owl_prev_id')
                                        Best Of Category-prev
                                    @endslot
                                    @slot('carousel_id')
                                        '{{\Illuminate\Support\Str::slug($product_you_may_like_title,'-')}}'
                                    @endslot
                                    @slot('owl_prev_id')
                                        'Best Of Category-prev'
                                    @endslot
                                    @slot('owl_next_id')
                                        'Best Of Category-next'
                                @endslot
                            @endcomponent
                        @endif
                        <!-- Product You May Like Ends Here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Detail Ends Here -->
@endsection
