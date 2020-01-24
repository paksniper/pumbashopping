@extends(\Illuminate\Support\Facades\Auth::guard('admin')->check() ?'layout.admin_app':'layout.app')

@section('title')
    PumbaShopping
@endsection
@section('content')
    <!-- Slide Show Starts Here -->

    <section id="slide-show">

        <div class="row">
            <div id="main-slide-show" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($sliders as $key => $slider)
                        <div class="carousel-item {{$key == 0 ? 'active' : ''}}" style="width: 100%;">
                            <a href="{{route('view_section_products',$slider->section)}}">
                                <img class="img-fluid slide-img w-100"
                                     src="{{asset('storage/images/sliders/'.$slider->slider_image)}}">
                            </a>
                        </div>
                    @endforeach
                </div>
                <a href="#main-slide-show" class="carousel-control-prev" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a href="#main-slide-show" class="carousel-control-next" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>
        </div>
    </section>
    <!-- Slide Show Ends Here -->
    @if(!$products->isEmpty())
        @php $count = 0; @endphp
        @foreach($sections as $section)
            @component('components.card_section')
                @slot('section_title')
                    {{\Illuminate\Support\Str::slug($section," ")}}
                @endslot
                @slot('section_view_url')
                    {{route('view_section_products',\Illuminate\Support\Str::slug($section,'-'))}}
                @endslot
                @slot('section_carousel_id')
                    {{$section}}
                    {{--best-of-cloth-carousel--}}
                @endslot
                @slot('product_container')
                    @foreach($products[$count] as $product )
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
                                        <span class="text-success">Rs.&nbsp{{$product->discount}}</span>
                                    @endif
                                @endslot

                                @slot('original_price')
                                    @if($product->discount != "")
                                        &nbsp<s
                                            class="text-muted"><small>Rs.&nbsp{{$product->price}}</small></s>
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
                @endslot
                @slot('section_carousel_id')
                    {{\Illuminate\Support\Str::slug($section,'-')}}
                @endslot
                @slot('owl_prev_id')
                    {{$section}}-prev
                @endslot
                @slot('carousel_id')
                    '{{\Illuminate\Support\Str::slug($section,'-')}}'
                @endslot
                @slot('owl_prev_id')
                    '{{\Illuminate\Support\Str::slug($section,'-')}}-prev'
                @endslot
                @slot('owl_next_id')
                    '{{\Illuminate\Support\Str::slug($section,'-')}}-next'
                @endslot
            @endcomponent
            @php $count++; @endphp
        @endforeach
    @else
        <div class="card col-md-6 border-0 mt-5 offset-md-3 text-center text-capitalize">
            <div class="card-body alert alert-danger">
                Sorry no product is available
            </div>
        </div>
    @endif
    <br>
    <br>
    <!--  Feature Brands Starts Here -->
    @if(!$feature_brands->isEmpty())
        @component('components.rest_card_section')
            @slot('carousel_container_style')

            @endslot
            @slot('section_title')
                {{$feature_brands_title}}
            @endslot

            @slot('section_carousel_id')
                {{$feature_brands_title}}

            @endslot
            @slot('product_container')
                @foreach($feature_brands as $brand )
                    <div id="card-inner-container" class="owl-item my-4">
                        @component('components.brand_card')
                            @slot('product_img_url')
                                {{route('view_brand_products',\Illuminate\Support\Str::slug($brand->title ,'-'))}}
                                @slot('image_source')
                                    {{asset('storage/images/brands/'.$brand->image)}}
                                @endslot
                            @endslot
                        @endcomponent
                    </div>
                @endforeach
            @endslot
            @slot('section_carousel_id')
                {{\Illuminate\Support\Str::slug($feature_brands_title,'-')}}
            @endslot
            @slot('owl_prev_id')
                Best Of Category-prev
            @endslot
            @slot('carousel_id')
                '{{\Illuminate\Support\Str::slug($feature_brands_title,'-')}}'
            @endslot
            @slot('owl_prev_id')
                'Best Of Category-prev'
            @endslot
            @slot('owl_next_id')
                'Best Of Category-next'
            @endslot
        @endcomponent
    @endif
    <!--  Feature Brands End Here -->
@endsection
