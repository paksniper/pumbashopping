@extends('layout.app')

@section('content')
    <!-- Displaying All Products Start Here -->
    <section id="product-detail" class="my-5">
        <div class="container p-0">
            <div class="row">
                <div class="col-md-3 h-auto border p-0 filter-section categories-container" id="{{$container_id}}"
                     title="{{$container_value}}">
                    <div class="p-2 border-bottom-0">
                        <h6 class="font-weight-bold">FILTERS</h6>
                    </div>
                    <div class="card mb-3 border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top p-2 bg-white categories-btn">
                            <div class="card-text float-left" style="font-size: 15px;">Categories</div>
                            <span class="float-right fas fa-angle-up cat-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body p-0 pl-1">
                            <ul class="list-unstyled categories mCustomScrollbar" data-mcs-theme="dark"
                                id="{{$fashion_value}}">
                                @if(!empty($fp))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="fashionyo_{{$fp}}">
                                            <label class="custom-control-label" for="fashionyo_{{$fp}}">
                                                    <span class="mb-1 d-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$fp}}</span>
                                            </label>
                                        </div>
                                    </li>
                                @elseif(!empty($cp))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="categoryyo_{{$cp}}">
                                            <label class="custom-control-label" for="categoryyo_{{$cp}}">
                                                    <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$cp}}</span>
                                            </label>
                                        </div>
                                    </li>
                                @elseif(!empty($scp) && !empty($categoryForSubcategory))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="{{$scp}}">
                                            <input type="hidden" id="ajax-subcategory" value="{{$scp}}">
                                            <label class="custom-control-label" for="{{$scp}}">
                                                    <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$scp}}</span>
                                            </label>
                                        </div>
                                    </li>
                                @elseif(!empty($sp))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="sectionyo_{{$section}}">
                                            <label class="custom-control-label" for="sectionyo_{{$section}}">
                                                    <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$section}}</span>
                                            </label>
                                        </div>
                                    </li>
                                    @foreach($sectionSubcategories as $subcategory)
                                        <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$subcategory}}">
                                                <label class="custom-control-label" for="{{$subcategory}}">
                                                        <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                              style="width: 200px;">{{$subcategory}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif(!empty($pb))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="brandyo_{{$brand}}">
                                            <label class="custom-control-label" for="brandyo_{{$brand}}">
                                                    <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$brand}}</span>
                                            </label>
                                        </div>
                                    </li>
                                    @foreach($brandSubcategories as $subcategory)
                                        <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$subcategory}}">
                                                <label class="custom-control-label" for="{{$subcategory}}">
                                                        <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                              style="width: 200px;">{{$subcategory}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif(!empty($tp))
                                    <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="traderyo_{{$trader}}">
                                            <label class="custom-control-label" for="traderyo_{{$trader}}">
                                                    <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                          style="width: 200px;">{{$trader}}</span>
                                            </label>
                                        </div>
                                    </li>
                                    @foreach($traderSubcategories as $subcategory)
                                        <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$subcategory}}">
                                                <label class="custom-control-label" for="{{$subcategory}}">
                                                        <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                              style="width: 200px;">{{$subcategory}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif(!empty($searchProduct))
                                    @foreach($searchSubcategories as $subcategory)
                                        <li class="text-truncate d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$subcategory}}">
                                                <label class="custom-control-label" for="{{$subcategory}}">
                                                        <span class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                              style="width: 200px;">{{$subcategory}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                                @if(!empty($fashionCategories))
                                    @foreach($fashionCategories as $category)
                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$category}}">
                                                <label class="custom-control-label" for="{{$category}}">
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                style="width: 200px;">{{\Illuminate\Support\Str::slug($category," ")}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif(!empty($catSubCategories))
                                    @foreach($catSubCategories as $subcategory)

                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$subcategory}}">
                                                <label class="custom-control-label" for="{{$subcategory}}">
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                style="width: 200px;">{{\Illuminate\Support\Str::slug($subcategory," ")}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif(!empty($subCategorie))
                                    <a href="{{route('view_subcategory_products',['$subcategory'=>\Illuminate\Support\Str::slug($subcat,'-'),'category'=>\Illuminate\Support\Str::slug($categoryForSubcategory,'-')])}}"
                                       class="subcategory-item-container">
                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <span
                                                class="ml-3 d-inline-block text-truncate subcategory-item-container"
                                                style="width: 200px;">{{\Illuminate\Support\Str::slug($subcat," ")}}</span>
                                        </li>
                                    </a>
                                @endif

                            </ul>
                        </div>
                    </div>
                    <div class="card border-top-0 mb-3 w-100 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header bg-white border border-left-0 border-right-0 border-bottom-1 px-3 py-1">
                            <div class="card-title" style="font-size: 15px;">
                                Price
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" min="{{$min_price}}" max="{{$max_price}}"
                                           oninput="validity.valid||(value='{{$min_price}}');"
                                           id="min_price"
                                           class="price-range-field w-75 border-left-dark" value="{{$min_price}}"/>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" min="{{$min_price}}" max="{{$max_price}}"
                                           oninput="validity.valid||(value='{{$max_price}}');"
                                           id="max_price" class="price-range-field w-75"/>
                                </div>
                            </div>
                            <div id="slider-range" class="price-filter-range w-100" name="rangeInput"
                                 style="height: 5px;">
                            </div>
                            <div id="price-display" style="font-size: 14px;">Rs.&nbsp;<span
                                    id="price_small">&nbsp;{{$min_price}}</span>&nbsp;- &nbsp;<span
                                    id="price_large">{{$max_price}}</span></div>
                        </div>
                    </div>
                    <div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white brands-btn">
                            <div class="card-text float-left" style="font-size: 15px;">Brands</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body brands-container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="brand" data-mcs-theme="dark">
                                @php
                                    $brand_count = 0;
                                @endphp
                                @while($brand_count < $brand_counter)
                                    @foreach($product_brands[$brand_count] as $brand)
                                        <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="{{$brand->title}}">
                                                <label class="custom-control-label" for="{{$brand->title}}">
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container"
                                                style="width: 200px;">{{\Illuminate\Support\Str::slug($brand->title," ")}}</span>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                    @php
                                        $brand_count++;
                                    @endphp
                                @endwhile
                            </ul>
                        </div>
                    </div>
                    <div id="filter-container">
                        @if($filter_html !== '')
                            {!! $filter_html !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-9 view-products-container">
                    @if($best_of_category->isNotEmpty())
                        @component('components.rest_card_section')
                            @slot('carousel_container_style')
                                box-shadow:none;
                            @endslot
                            @slot('section_title')
                                {{$best_of_category_title}}
                            @endslot

                            @slot('section_carousel_id')
                                {{$best_of_category_title}}

                            @endslot
                            @slot('product_container')
                                @foreach($best_of_category as $product )
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
                                {{\Illuminate\Support\Str::slug($best_of_category_title,'-')}}
                            @endslot
                            @slot('owl_prev_id')
                                Best Of Category-prev
                            @endslot
                            @slot('carousel_id')
                                '{{\Illuminate\Support\Str::slug($best_of_category_title,'-')}}'
                            @endslot
                            @slot('owl_prev_id')
                                'Best Of Category-prev'
                            @endslot
                            @slot('owl_next_id')
                                'Best Of Category-next'
                            @endslot
                        @endcomponent
                    @endif
                    <div class="card bg-white">
                        <div class="card-header bg-white" id="product-result-status">
                            @if($viewproducts !== "")
                                @if(!empty($pb))
                                    <span class="view-header">{{$pb}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($cp))
                                    <span class="view-header">{{$cp}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($scp))
                                    <span class="view-header">{{$scp}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($fp))
                                    <span class="view-header">{{$fp}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($tp))
                                    <span class="view-header">{{$tp}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($searchProduct))
                                    <span class="view-header">{{$searchProduct}}&nbsp;({{$count}} Products Found)</span>
                                @elseif(!empty($sp))
                                    <span class="view-header">{{$sp}}&nbsp;({{$count}} Products Found)</span>
                                @endif
                            @else
                                <span class="view-header"> No Product Found</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @php
                                $count = 0;
                            @endphp
                            <div class="row mb-2" id="product-wrapper">
                                @if(!$viewproducts->isEmpty())
                                    @foreach($viewproducts as $product)
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
                                            @php
                                                $count++;
                                            @endphp
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
                                Showing Page {{$viewproducts->currentPage()}} of {{$viewproducts->lastPage()}}
                            @endif
                        </div>
                        <div class="col d-flex justify-content-end">
                            @if(!empty($count))
                                {{$viewproducts->links()}}
                            @endif
                        </div>

                    </div>
                    <!-- Pagination ends here -->
                </div>
            </div>
        </div>
    </section>
    <!-- Product Detail Ends Here -->

@endsection
