<section id="best-for-you-section" class="mb-4">
    <div class="container">
        <div class="row text-center">
            <div id="carousel-container" style="{{$carousel_container_style}}" class="card">
                <div class="card-header bg-white clearfix">
                    <span style="text-transform: capitalize;"
                          class="float-left">{{$section_title}}</span>
                </div>
                <div class="card-body" style="position: relative;">
                    <div id="{{$section_carousel_id}}" class="owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage" style="margin: 0px; overflow: hidden;">
                                {{$product_container}}
                            </div>
                        </div>
                    </div>
                    <div class="owl-nav">
                        <div id="{{$owl_prev_id}}"
                             class="owl-prev border-right rounded-right align-middle d-flex align-items-center py-5 px-1"
                             onclick="owlPrevFunction({{$carousel_id}})">
                            <span class="fa fa-angle-left fa-2x"></span>
                        </div>
                        <div id="{{$owl_next_id}}"
                             class="owl-next border-right rounded-left align-middle d-flex align-items-center py-5 px-1"
                             onclick="owlNextFunction({{$carousel_id}})">
                            <span class="fa fa-angle-right fa-2x"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
