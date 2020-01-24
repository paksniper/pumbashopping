<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"
          integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/owlcarousel/dist/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{'assets/owlcarousel/dist/assets/owl.theme.default.min.css'}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="{{asset('assets/customScrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('assets/crossplatformnavigation/dist/css/sm-core-css.css')}}" rel="stylesheet">
    <link href="{{asset('assets/loader/preloader.css')}}" rel="stylesheet">
    <link href="{{asset('assets/crossplatformnavigation/dist/css/sm-mint/sm-mint.css')}}" rel="stylesheet">
    <link href="{{asset('assets/jqueryui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/rangeslider/price_range_style.css')}}" rel="stylesheet">
    @stack('link')
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>@yield('title')</title>
</head>
<body>
<nav id="store-navbar" class="navbar navbar-expand-sm navbar-dark p-0">
    <div class="row mx-auto">
        <a href="/" class="navbar-brand pt-0 mr-0 "><img src='{{asset("images/pumbastore.png")}}'
                                                         class="img-responsive"></a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#mynavbar"><span
                class="navbar-toggler-icon"></span></button>
        <div id="mynavbar" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ml-3" style="position:relative;">
                    <form action="{{route('productSubmitSearch')}}" class="form-inline mb-0" name="search-form"
                          id="search-form" method="GET"
                          enctype="application/x-www-form-urlencoded">
                        {{--                        @csrf--}}
                        <div class="input-group">
                            <select id="nav-categories" name="category"
                                    class="text-capitalize custom-select input-group-prepend py-0">
                                <option value="all">All</option>
                                @foreach($categories as $category)
                                    <option
                                        value="{{$category->fashion}}">{{\Illuminate\Support\Str::slug($category->fashion,' ')}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="search-input" id="search-input"
                                   placeholder="Search product here..." onkeyup="searchProduct()" class="form-control"
                                   style="width: 400px;">
                            <button id="search-submit-btn" type="submit" class="btn btn-light input-group-append">
                                <span class="fas fa-search"></span>
                            </button>
                        </div>
                    </form>
                    <ul style="z-index: 5;" class="border w-100 pl-0 bg-white suggested-product"></ul>
                </li>
                @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
                    <li class="nav-item ml-4" style="font-size: 17px;">
                        <a href="{{route('admin_home_view')}}" class="nav-link"><span
                                class="fas fa-fw fa-tachometer-alt"></span> &nbsp;DashBoard</a>
                    </li>
                @else
                    <li class="nav-item ml-4">
                        <a href="#" class="nav-link" style="font-size: 16px;"><span class="fas fa-home"></span>
                            &nbsp;Home</a>
                    </li>
                    <li class="nav-item ml-3">
                        <a href="{{route('customer-service')}}" class="nav-link" style="font-size:16px;"><span
                                class="fas fa-headset"></span> &nbsp;Customer
                            Service</a>
                    </li>
                @endif
            </ul>
            @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <span style="font-size: 15px;">
                                @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
                                    {{$admin->name}}&nbsp;
                                @endif
                            </span>
                                @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
                                    <img
                                        width="50" height="50"
                                        class="img-fluid border-0 rounded-circle"
                                        src="{{asset('storage/images/admin/'.$admin->image)}}">
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{route('admin_profile_view',\Illuminate\Support\Facades\Auth::guard('admin')->user()->id)}}"
                                   class="dropdown-item">
                                    Edit Profile
                                </a>
                                <a href="{{route('admin_logout')}}" class="dropdown-item">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
@if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
    <div class="row border border-top-0 control-panel-wrapper px-4 py-2 ">
        <div class="col-md px-1">
            <a href="{{route('admin_product_view')}}" class="control-panel-item"> <i
                    class="fab fa-product-hunt"></i> Product </a>
        </div>
        <div class="col-md px-1">
            <a href="{{route('admin_brand_view')}}" class="control-panel-item"> <i class="fab fa-blogger-b"></i>
                Brand </a>
        </div>
        <div class="col-md px-1">
            <a href="{{route('admin_slider_view')}}" class="control-panel-item"> <i class="far fa-images"></i>
                Slider </a>
        </div>
        <div class="col-md px-1">
            <a href="{{route('admin_section_view')}}" class="control-panel-item"> <i class="fas fa-chart-pie"></i>
                Section </a>
        </div>
        <div class="col-md px-1">
            <a href="{{route('admin_category_view')}}" class="control-panel-item"> <i class="fas fa-cubes"></i>
                Category </a>
        </div>
        <div class="col-md-2 px-1">
            <a href="{{route('admin_subcategory_view')}}" class="control-panel-item"> <i
                    class="fas fa-network-wired"></i> Subcategory </a>
        </div>
        <div class="col-md">
            <a href="{{route('admin_fashion_view')}}" class="control-panel-item"> <i class="fas fa-icons"></i>
                Fashion </a>
        </div>
        <div class="col-md">
            <a href="{{route('admin_filter_view')}}" class="control-panel-item"> <i class="fas fa-filter"></i>
                Filter </a>
        </div>
        <div class="col-md-2 px-1">
            <a href="{{route('admin_filter_subcategory_view')}}" class="control-panel-item"> <i
                    class="fas fa-network-wired"></i> Filter Subcategory </a>
        </div>
    </div>
@endif
<div class="row bg-white p-0">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <ul id="main-menu" class="sm sm-mint" style="z-index: 3 !important;">
                    <li><a href="#" style="color: #c23616; padding-left: 0 !important;"><i
                                class=" px-0 fas fa-align-left"></i>&nbsp;&nbsp;All
                            Categories</a>
                        <ul id="fashion-categories">
                            {!! $html !!}
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-right pt-2" style="color: #c23616;">
                <i class="fas fa-phone-alt"></i><span style="font-size: 15px;"> (091-5234343)</span>
            </div>
        </div>
    </div>
</div>
@yield('content')

<footer class="pt-5 pb-4 mt-5" style="width: 100%;">
    <div class="container p-0">
        <div class="row">
            <div class="col-md-3">
                <h7 class="text-white">About Us</h7>
                <ul class="list-unstyled mt-2">
                    <li><a href="{{route('terms-conditions')}}"><small>Terms &amp; Conditions</small></a></li>
                    {{--                    <li><a href="#"><small>Privacy Agreement</small></a></li>--}}
                </ul>
            </div>
            <div class="col-md-3">
                <h7 class="text-white">Customer Service</h7>
                <ul class="list-unstyled mt-2">
                    <li><a href="#"><small>Contact Us</small></a></li>
                    {{--                    <li><a href="#"><small>Refund Policy</small></a></li>--}}
                    <li><a href="{{route('return-policy')}}"><small>Return Policy</small></a></li>
                    {{--                    <li><a href="#"><small>Warranty Policy</small></a></li>--}}
                </ul>
            </div>
            <div class="col-md-3 ">
                <h7 class="text-white">Touch With Us On Social</h7>
                <ul class="list-unstyled mt-2">
                    <li>
                        <a href="#"><span
                                class="fab fa-facebook-square"></span>&nbsp;&nbsp;&nbsp;<small>Facebook</small></a>
                    </li>
                    <li>
                        <a href="#"><span class="fab fa-twitter-square"></span>&nbsp;&nbsp;&nbsp;<small>Twitter</small></a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/pumbashopping/"><span class="fab fa-instagram"></span>&nbsp;&nbsp;&nbsp;<small>Instagram</small></a>
                    </li>
                </ul>
            </div>
            <div class="col">
                <form id="newsletter-form">
                    @csrf
                <h7 class="text-white">Subscribe To Our Newsletter</h7>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newsletter_email" name="newsletter_email" @error('newsletter_email') is-invalid @enderror placeholder="Email...">
                        <div class="input-group-append">
                            <span class="input-group-text far fa-envelope"></span>
                        </div>
                    </div>
                    <div class="text-white mt-2" id="newsletter-status-msg" style="font-size: 15px;"></div>
                    <div class="form-group mt-3">
                        <button type="submit" id="newsletter-submission" class="newsletter-btn">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-bottom mt-3">
        <div class="container">
            <div class="row">
                <div class="btn-to-top border-0 p-2 rounded-circle text-center"><a href="#store-navbar"
                                                                                   class=" fas fa-angle-up "></a>
                </div>
                <div class="copy-right mt-2"><small>&copy;2019 pumbashoping.com.pk. All Rights Reserved.</small></div>
            </div>
        </div>
    </div>
</footer>

<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
{{--<script src="{{asset('assets/simpleInImageZoom/jquery.zoom.js')}}"></script>--}}
<script src="{{asset('assets/lightweight/jquery.zoomtoo.js')}}"></script>
<script src="{{asset('assets/customScrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('assets/owlcarousel/dist/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/crossplatformnavigation/dist/jquery.smartmenus.js')}}"></script>
<script src="{{asset('assets/loader/jquery.preloader.js')}}"></script>
<script src="{{asset('assets/jqueryui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/rangeslider/price_range_script.js')}}"></script>
@stack('script')
<script src="{{asset('js/script.js')}}"></script>
<script>

    function owlPrevFunction(carousel) {
        var carousels = $('#' + carousel);
        carousels.owlCarousel();
        carousels.trigger('prev.owl.carousel');
    }

    function owlNextFunction(carousel) {
        var carousels = $('#' + carousel);
        carousels.owlCarousel();
        carousels.trigger('next.owl.carousel');
    }

    $('body').scrollspy({target: '.btn-to-top'});
    $('.btn-to-top a').on('click', function (e) {

        if (this.hash !== '') {
            e.preventDefault();
            const hash = this.hash;
            $('html,body').animate({
                scrollTop: $(hash).offset().top
            }, 900, function () {
                window.location.hash = hash;
            })

        }
    });

    $('#main-menu').smartmenus({
        subMenusSubOffsetX: 1,
        subMenusSubOffsetY: -8
    });


    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/5df4701143be710e1d220a46/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();

    // $("#slider-range").slider({
    //     range: true,
    //     orientation: "horizontal",
    //     min: 0,
    //     max: 5000,
    //     values: [0, 10000],
    //
    //     slide: function (event, ui) {
    //         if (ui.values[0] === ui.values[1]) {
    //             return false;
    //         }
    //
    //         console.log('draggingyo:' + ui.values[0]);
    //         $("#min_price").val(ui.values[0]);
    //         $("#max_price").val(ui.values[1]);
    //     }
    // });
</script>
</body>
</html>

