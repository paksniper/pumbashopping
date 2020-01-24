$(document).ready(function(){

	$('#price-range-submit').hide();

	$("#min_price,#max_price").on('change', function () {

	  $('#price-range-submit').show();

	  var min_price_range = parseInt($("#min_price").val());

	  var max_price_range = parseInt($("#max_price").val());

	  if (min_price_range > max_price_range) {
		$('#max_price').val(min_price_range);
	  }

	  $("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });

	});


	$("#min_price,#max_price").on("paste keyup", function () {

	  $('#price-range-submit').show();

	  var min_price_range = parseInt($("#min_price").val());

	  var max_price_range = parseInt($("#max_price").val());

	  if(min_price_range == max_price_range){

			max_price_range = min_price_range + 100;

			$("#min_price").val(min_price_range);
			$("#max_price").val(max_price_range);
	  }

	  $("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });

	});


	$(function () {
	  $("#slider-range").slider({
		range: true,
		orientation: "horizontal",
		min: window.min_price,
		max: window.max_price,
		values: [window.min_price, window.max_price],
		step: 1,
		slide: function (event, ui) {
		  if (ui.values[0] === ui.values[1]) {
			  return false;
		  }
            // console.log('dragging:'+ui.values[0]);
		  $("#min_price").val(ui.values[0]);
		  $("#max_price").val(ui.values[1]);
		}

	  });

	  $("#min_price").val($("#slider-range").slider("values", 0));
	  $("#max_price").val($("#slider-range").slider("values", 1));

	});

    $('#min_price , #max_price').on('keypress focusout',function(event){
        if(event.which === 13) {
            if ($('#min_price').val() < window.min_price) {
                alert('Invalid input, Please select valid range price!');
                $('#min_price').val(window.min_price);
            }else if($('#max_price').val() > window.max_price){
                alert('Invalid input, Please select valid range price!');
                $('#max_price').val(window.max_price);
            } else {
                rangeSlider($('#min_price').val(),$('#max_price').val());
            }
        }
        console.log('focus is working'+$('#min_price').val() +'  ' + $('#max_price').val());
    });

    $('#min_price , #max_price').on('focusout',function(event){
        rangeSlider($('#min_price').val(),$('#max_price').val());
    });

    $('#slider-range').on('slidestop',function(event,ui){
       rangeSlider(ui.values[0],ui.values[1]);
    });
});

function rangeSlider(min_price=window.min_price,max_price=window.max_price) {
    // console.log('range slider is called');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.view-products-container').preloader({
        text: ''
    });

    var checkboxes = [];
    var container = $('.filter-section').attr('id');
    var container_value = $('.filter-section').attr('title');
    // alert(container_value);

    $('.filter-section' + ' input:checked').each(function (index, el) {
        // checkboxes.push($(this).parent().find('span').text());
        // var table_column = $(this).parent().parent().parent().parent().parent().attr('id');
        var table_column = $(this).parents('ul').attr('id');
        var value = $(this).parent().find('span').text();
        var id = $(this).parent().find('input').attr('id');


        if (index === 0) {

            if(id.includes('fashionyo_')) {

                checkboxes.push(['fashion', [value]]);
            } else if(id.includes('categoryyo_')) {
                checkboxes.push(['category', [value]]);
            } else {
                checkboxes.push([table_column, [value]]);
            }
        } else {
            for (let i = 0; i < checkboxes.length; i++) {

                if (checkboxes[i][0] === table_column && !id.includes('fashionyo_')) {
                    // alert('if part is executed');
                    if (!checkboxes[i][1].includes(value)) {
                        checkboxes[i][1].push(value);
                    }
                } else if (typeof checkboxes[i + 1] === 'undefined') {
                    // alert('else part is executed');
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
                // alert('from range script '+response.checked_categories);
                console.log(response.min_price);

                console.log(response.result);
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
                console.log(response.min_price);

                console.log(response.result);
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
    } else if(container === "subcategory") {
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
                // alert(response.checked_categories);
                console.log(response.min_price);

                console.log(response.result);
                // $('#filter-container').empty();
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
                console.log(response.min_price);

                console.log(response.result);
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
                console.log(response.min_price);

                console.log(response.result);
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
                console.log(response.min_price);

                console.log(response.result);
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
                console.log(response.min_price);

                console.log(response.result);
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

    // console.log('slider function give response min price '+min_price+ ' and max price '+max_price + ' checked: '+checkboxes+container);
}
