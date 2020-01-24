$(document).ready(function () {
    $('.carousel').carousel({
        interval: 5000
    });


    $('.owl-carousel').owlCarousel({
        items: 6,
        autoWidth: true,
        margin: 4,
        loop: true,
        dots: false,
        responsiveClass:true
    });
    $('#slide-show').owlCarousel({
        navigation: true, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true
    });


    $('.categories').mCustomScrollbar({
        theme: 'dark'
    });
    $('.brands').mCustomScrollbar({
        theme: 'dark'
    });

    $("#zoom-on-img").zoomToo({
        magnify: 1.1
    });

    $('.categories-btn').click(function () {
        $('.categories').slideToggle(200, 'linear');
        var icon = $(this).find('span');
        if (icon.hasClass('fa-angle-down')) {
            icon.addClass('fa-angle-up').removeClass('fa-angle-down');
        } else {
            icon.addClass('fa-angle-down').removeClass('fa-angle-up');
        }
    });
    $('.brands-btn').click(function () {
        $('.brands-container').slideToggle(200, 'linear');
        var icon = $(this).find('span');
        if (icon.hasClass('fa-angle-down')) {
            icon.addClass('fa-angle-up').removeClass('fa-angle-down');
        } else {
            icon.addClass('fa-angle-down').removeClass('fa-angle-up');
        }
    });

    $('#data-table').dataTable({
        "oLanguage": {
            "oPaginate": {
                "sPrevious": "&#60;",
                "sNext": "&#62;"
            }
        }
    });

    CKEDITOR.replace('product_description');
    CKEDITOR.replace('product_specification');
    CKEDITOR.replace('product_feature');

    //for subcategory in admin section
    $('#subcat-fashion').change(function (event) {
        // console.log(event.target.value);
        $("#category").empty();
        $("#subcategory").empty();
        var fashion = event.target.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            dataType: 'json',
            url: "/filter/search-fashion-categories",
            method: "post",
            data: {fashion: fashion},
            success: function (response) {
                if (response.result === null) {
                    $('#category').append("<option value=''>No Category Available</option>");
                } else {
                    $('#category').prepend("<option value=''>Select Category</option>");
                    $('#category').append(response.result);
                    // console.log(response);
                }
            }
        });
    });


    $('#fashion').change(function (event) {
        // console.log(event.target.value);
        $("#category").empty();
        $("#subcategory").empty();
        var fashion = event.target.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            dataType: 'json',
            url: "/filter/search-fashion-categories",
            method: "post",
            data: {fashion: fashion},
            success: function (response) {
                if (response.result === null) {
                    $('#category').append("<option value=''>No Category Available</option>");
                } else {
                    $('#category').prepend("<option value=''>Select Category</option>");
                    $('#category').append(response.result);
                    // console.log(response);
                }
            }
        });


    });

    $("#category").change(function (event) {
        // console.log(event.target.value);
        $("#subcategory").empty();
        var category = event.target.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            dataType: 'json',
            url: "/filter/search-category-subcategories",
            method: "post",
            data: {category: category},
            success: function (response) {
                if (response.result === null) {
                    $('#subcategory').append("<option value=''>No Subcategory Available</option>");
                } else {
                    $('#subcategory').prepend("<option value=''>Select Subcategory</option>");
                    $('#subcategory').append(response.result);
                    // console.log(response);
                }
            }
        });
    });


    $('#filter-fashion').change(function (event) {
        // console.log(event.target.value);
        $("#filter-category").empty();
        var fashion = event.target.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            dataType: 'json',
            url: "/filter/search-filter-fashion-category",
            method: "post",
            data: {fashion: fashion},
            success: function (response) {
                if (response.result === null) {
                    $('#filter-category').append("<option value=''>No Category Available</option>");
                } else {
                    $('#filter-category').prepend("<option value=''>Select Category</option>");
                    $('#filter-category').append(response.result);
                    // console.log(response);
                }
            }
        });


    });

    $('#filter-selection').change(function (event) {
        console.log(event.target.value);
        var filter_category = event.target.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (filter_category !== "" && filter_category !== "reset-filter") {
            var category = $('#filter-selection-container').find('#' + filter_category.replace(' ', '_'));
            if (filter_category.replace(' ', '_') !== category.attr('id')) {
                $.ajax({
                    dataType: 'json',
                    url: "/filter/search-filter-selection-category-subcategory",
                    method: "post",
                    data: {filter_category: filter_category},
                    success: function (response) {
                        if (response.result === null) {
                            // $('#filter-selection-container').append("<option value=''>No Subcategory Available</option>");
                        } else {
                            $('#filter-selection-container').append(response.result);
                            // console.log(response);
                        }
                    }
                });
            } else {
                console.log('not found');
            }
        } else if (filter_category !== "" && filter_category === "reset-filter") {
            $('#filter-selection-container').contents(':not("#selection-wrapper")').remove();
        }

    });


    $(".delete_btn").click(function () {
        var form_id = $(this).attr('id');
        // alert(form_id);
        $("#delete_form" + form_id).submit();
    });


});


$('#newsletter-form').submit(function(e) {
    e.preventDefault();
    var newsletter_email = $('#newsletter_email').val();
    // alert(newsletter_email);
    if(newsletter_email !== "") {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            }
        });
        $.ajax({
            url: "/ajax/subscribe-to-newsletter",
            method: "post",
            data: {email:newsletter_email},
            dataType: 'json',
            success: function (response) {
               if(response.success) {
                   $('#newsletter_email').val('');
                   $('#newsletter-status-msg').empty();
                   $('#newsletter-status-msg').text('Successfully subscribed');
               } else {
                   $('#newsletter-status-msg').empty();
                   $('#newsletter-status-msg').text(response.fail);
               }
            }
        });
    }
});

function searchProduct() {
    var product_container = $(".suggested-product");
    var search_input = $("#search-input");
    var category = $('#nav-categories');
    if (search_input.val() !== "") {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            }
        });
        $.ajax({
            url: "/filter/search-product",
            method: "post",
            data: {search: search_input.val(), category: category.val()},
            success: function (response) {
                if (response.result === "") {
                    product_container.css('display', 'none');
                }
                console.log(response);
                product_container.empty();
                product_container.append(response.result);
                $(".suggested-product hr:last-child").remove();
            }
        });
        product_container.css('display', 'block');
        // product_container.append('<p>Hi</p>');
    } else if (search_input.val() === "") {
        product_container.empty();
        product_container.css('display', 'none');
    }

}

function filter(container) {
    $('.' + container + '_container').slideToggle(200, 'linear');
    var icon = $('.collapse_' + container).find('span');
    if (icon.hasClass('fa-angle-down')) {
        icon.addClass('fa-angle-up').removeClass('fa-angle-down');
    } else {
        icon.addClass('fa-angle-down').removeClass('fa-angle-up');
    }
}

// $('#slider-range').on('slidestop',function(){
//     alert('range slider is working');
// });
$('.filter-section').on('change','ul li',function (event) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.view-products-container').preloader({
        text: '',
    });

    var checkboxes = [];
    var container = $('.filter-section').attr('id');
    var container_value = $('.filter-section').attr('title');
    // alert(container+ '  '+container_value);

    $('.filter-section' + ' input:checked').each(function (index, el) {
        // checkboxes.push($(this).parent().find('span').text());
        // var table_column = $(this).parent().parent().parent().parent().parent().attr('id');
        var table_column = $(this).parents('ul').attr('id');
        var value = $(this).parent().find('span').text();
        var id = $(this).parent().find('input').attr('id');
        // alert('id = '+id+' table_column = '+table_column+' value = '+value);


        if (index === 0) {
            if(id.includes('fashionyo_')) {
                checkboxes.push(['fashion', [value]]);
            } else if(id.includes('categoryyo_')) {
                checkboxes.push(['category', [value]]);
                // alert('category yo is executed');
            } else if(id.includes('sectionyo_')) {
                checkboxes.push(['section', [value]]);
                // alert('section is checked');
            } else if(id.includes('brandyo_')) {
                checkboxes.push(['brandz', [value]]);
            } else if(id.includes('traderyo_')){
                checkboxes.push(['traderz', [value]]);
            } else {
                checkboxes.push([table_column, [value]]);
                // alert('else is executed');
            }
        } else {
            for (let i = 0; i < checkboxes.length; i++) {

                if (checkboxes[i][0] === table_column && !id.includes('fashionyo_') && !id.includes('categoryyo_')
                    && !id.includes('sectionyo_')) {
                    if (!checkboxes[i][1].includes(value)) {
                        checkboxes[i][1].push(value);
                    }
                } else if (typeof checkboxes[i + 1] === 'undefined') {
                    checkboxes.push([table_column, [value]]);
                }
            }
        }
    });
    console.log(checkboxes);

    if(container === "fashion") {

        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/search/q",
            method: "get",
            data: {search: checkboxes,fashion:container_value},
            success: function (response) {
                // alert('min price = '+response.min_price+' and max price = '+response.max_price);
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.fashion + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
        // console.log(checkboxes);
    } else if(container === "category") {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/category/search/q",
            method: "get",
            data: {search: checkboxes,category:container_value},
            success: function (response) {
                // alert(response.checked_categories);
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.category + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    }else if(container === "subcategory") {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        var subcategory = $('#ajax-subcategory').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/subcategory/search/q",
            method: "get",
            data: {search: checkboxes,category:container_value,subcategory:subcategory},
            success: function (response) {
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.subcategory + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    } else if(container === "section") {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/section/search/q",
            method: "get",
            data: {search: checkboxes,section:container_value},
            success: function (response) {
                // alert(response.checked_categories);
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.section + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    } else if(container === "brandz") {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/brand/search/q",
            method: "get",
            data: {search: checkboxes,brand:container_value},
            success: function (response) {
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.brand + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    } else if(container === "traderz") {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/trader/search/q",
            method: "get",
            data: {search: checkboxes,trader:container_value},
            success: function (response) {
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.trader + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    } else if(container === "searchz") {
        // alert('search is called');
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        checkboxes.push(['price',[min_price,max_price]]);
        $.ajax({
            dataType: 'json',
            url: "/filter/products/search/search/q",
            method: "get",
            data: {search: checkboxes,searchz:container_value},
            success: function (response) {
                // console.log(response.min_price);
                //
                // console.log(response.result);
                // $('#filter-container').empty();
                if(response.filter_html !== false) {
                    $('#filter-container').empty();
                    $('#filter-container').append(response.filter_html);
                }
                $('#product-wrapper').empty();
                $('#product-wrapper').append(response.result);
                $('.view-header').empty();
                $('.view-header').append(response.search + '&nbsp;(<span id="counts">' + response.count + '</span> products found)');
                $('.view-products-container').preloader('remove');
                if(response.min_price !== null && response.max_price !== null) {
                    $('#min_price').val(response.min_price);
                    $('#max_price').val(response.max_price);
                    $('#price_small').text(response.min_price);
                    $('#price_large').text(response.max_price);
                    $('#slider-range').slider({
                        min: response.min_price,
                        max: response.max_price,
                        values: [$('#min_price').val(), $('#max_price').val()],
                    });
                }
                $.each(response.checked_categories,function(index,value){
                    // alert(value);
                    $('[title|='+value+']').prop('checked',true);
                });
            }
        });
    }
});

//collapse filter container on load
function collapseFilter() {
    for (var i = 0; i < window.filter_holders.length; i++) {
        $('.' + window.filter_holders[i] + '_container').slideToggle(200, 'linear');
        var icon = $('.collapse_' + window.filter_holders[i]).find('span');
        if (icon.hasClass('fa-angle-down')) {
            icon.addClass('fa-angle-up').removeClass('fa-angle-down');
        } else {
            icon.addClass('fa-angle-down').removeClass('fa-angle-up');
        }
    }
}


collapseFilter();
