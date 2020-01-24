<div class="card border-0 rounded-0 product-card">
    <a href="{{$product_img_url}}">
        <img class="img-fluid card-img-top"  src="{{$image_source}}">
    </a>
    <div class="card-body px-2 text-center">
        <p class="text-truncate view-product-title">
            <a href="{{$product_title_url}}" class="text-dark" style="text-decoration: none;">{{$product_title}}</a>
        </p>
        <p class="view-product-price-wrapper">
            {{$discount_price}}{{$original_price}}{{$discount_percentage}}
        </p>
    </div>
</div>
