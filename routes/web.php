<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Category;
use App\Mail\ForgotPassword;
use App\Mail\newsletter;
use App\Product;
use http\Url;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;


//Home page
Route::get('/', function () {
    return view('home');
})->name('home');

//Custom service
Route::get('/customer-service',function(){
   return view('customer_service');
})->name('customer-service');

//Terms and Conditions
Route::get('/terms-conditions',function(){
   return view('terms_and_conditions');
})->name('terms-conditions');

//Return policy
Route::get('/return-policy',function(){
   return view('return_policy');
})->name('return-policy');

//subscribe to newsletter
Route::post('/ajax/subscribe-to-newsletter','NewsletterController@subscribeToNewsletter')->name('subscribe');

//search products on key pressed
Route::post('/filter/search-product', 'SearchProduct@searchForProduct')->name('productSearch');

//search products on form submit
Route::get('/submit/search-product', 'SearchProduct@searchSubmitProduct')->name('productSubmitSearch');

//search categories on fashion selection
Route::post('/filter/search-fashion-categories', 'SearchProduct@searchForCategories')->name('categorySearch');

//search filter category on fashion selection
Route::post('/filter/search-filter-fashion-category', 'SearchProduct@searchForFilterCategories')->name('filtercategorySearch');

//search filter selection category for subcategories selection
Route::post('/filter/search-filter-selection-category-subcategory', 'SearchProduct@searchForFilterSelection')->name('filtercategorysubcategoriesSearch');

//search categories on fashion selection
Route::post('/filter/search-fashion-categories-list', 'SearchProduct@searchForCategoriesList')->name('categoryListSearch');

//search categories on fashion selection
Route::post('/filter/search-category-subcategories', 'SearchProduct@searchForSubcategories')->name('subcategoriesSearch');

//product detail
Route::get('/{title}', 'ProductDetail@displayProduct')->name('product_detail');

//view section products
Route::get('/section/{viewProducts}', 'View_all_products@viewProducts')->name('view_all_products');

//view all category products
Route::get('/category/search/q/{category}', 'View_all_products@viewCategoryProducts')->name('view_category_products');

//view all ajax fashion products
Route::get('/filter/products/search/q', 'View_all_products@viewAjaxFilterProducts')->name('view_ajax_fashion_products');

//view all ajax category products
Route::get('/filter/products/category/search/q', 'View_all_products@viewAjaxCategoryProducts')->name('view_ajax_category_products');

//view all subcategory products
Route::get('/subcategory/search/q/{subcategory}/{category}', 'View_all_products@viewSubcategoryProducts')->name('view_subcategory_products');

//view all ajax subcategory products
Route::get('/filter/products/subcategory/search/q', 'View_all_products@viewAjaxSubcategoryProducts')->name('view_ajax_subcategory_products');

//view all section  products
Route::get('/section/search/q/{section}', 'View_all_products@viewSectionProducts')->name('view_section_products');

//view all ajax section products
Route::get('/filter/products/section/search/q', 'View_all_products@viewAjaxSectionProducts')->name('view_ajax_section_products');

//view all brand  products
Route::get('/brand/search/q/{brand}', 'View_all_products@viewBrandProducts')->name('view_brand_products');

//view all ajax brand products
Route::get('/filter/products/brand/search/q', 'View_all_products@viewAjaxBrandProducts')->name('view_ajax_brand_products');

//view all trader  products
Route::get('/trader/search/q/{trader}', 'View_all_products@viewTraderProducts')->name('view_trader_products');

//view all ajax trader products
Route::get('/filter/products/trader/search/q', 'View_all_products@viewAjaxTraderProducts')->name('view_ajax_trader_products');

//view all ajax trader products
Route::get('/filter/products/search/search/q', 'View_all_products@viewAjaxSearchProducts')->name('view_ajax_trader_products');


//view all fashion category products
Route::get('/fashion/search/q/{fashion}', 'View_all_products@viewFashionProducts')->name('view_fashion_products');

//view all ajax fashion category products
Route::get('/fashion/search/q', 'View_all_products@viewAjaxFashionProducts')->name('view_ajax_fashion_products');

//get admin product view
Route::get('/admin/view/product', 'Product@getProductView')->name('admin_product_view')->middleware('auth:admin');

//get admin brand view
Route::get('/admin/view/brand', 'BrandController@getBrandView')->name('admin_brand_view')->middleware('auth:admin');

//get admin section view
Route::get('/admin/view/section', 'SectionController@getSectionView')->name('admin_section_view')->middleware('auth:admin');

//get admin slider view
Route::get('/admin/view/slider', 'SliderController@getSliderView')->name('admin_slider_view')->middleware('auth:admin');

//get admin category view
Route::get('/admin/view/category', 'CategoryController@getCategoryView')->name('admin_category_view')->middleware('auth:admin');

//get admin subcategory view
Route::get('/admin/view/subcategory', 'SubcategoryController@getSubcategoryView')->name('admin_subcategory_view')->middleware('auth:admin');

//get admin fashion view
Route::get('/admin/view/fashion', 'FashionController@getFashionView')->name('admin_fashion_view')->middleware('auth:admin');

//get admin filter view
Route::get('/admin/view/filter', 'FilterController@getFilterView')->name('admin_filter_view')->middleware('auth:admin');

//get admin filter subcategory view
Route::get('/admin/view/filter-subcategory', 'FilterSubcategoryController@getFilterSubcategoryView')->name('admin_filter_subcategory_view')->middleware('auth:admin');


//post submitted product
Route::post('admin/submission/product', 'Product@createProduct')->name('create_product')->middleware('auth:admin');

//post submitted brand
Route::post('admin/submission/brand', 'BrandController@createBrand')->name('create_brand')->middleware('auth:admin');

//post submitted section
Route::post('admin/submission/section', 'SectionController@createSection')->name('create_section')->middleware('auth:admin');

//post submitted slider
Route::post('admin/submission/slider', 'SliderController@createSlider')->name('create_slider')->middleware('auth:admin');

//post submitted category
Route::post('admin/submission/category', 'CategoryController@createCategory')->name('create_category')->middleware('auth:admin');

//post submitted subcategory
Route::post('admin/submission/subcategory', 'SubcategoryController@createSubcategory')->name('create_subcategory')->middleware('auth:admin');

//post submitted fashion
Route::post('admin/submission/fashion', 'FashionController@createFashion')->name('create_fashion')->middleware('auth:admin');

//post submitted filter
Route::post('admin/submission/filter', 'FilterController@createFilter')->name('create_filter_category')->middleware('auth:admin');

//post submitted filter subcategory
Route::post('admin/submission/filter-subcategory', 'FilterSubcategoryController@createFilterSubcategory')->name('create_filter_subcategory')->middleware('auth:admin');


//edit product view
Route::get('admin/edit/product/{id}', 'Product@editProductView')->name('admin_edit_product_view')->middleware('auth:admin');

//edit brand view
Route::get('admin/edit/brand/{id}', 'BrandController@editBrandView')->name('admin_edit_brand_view')->middleware('auth:admin');

//edit slider view
Route::get('admin/edit/slider/{id}', 'SliderController@editSliderView')->name('admin_edit_slider_view')->middleware('auth:admin');

//edit section view
Route::get('admin/edit/section/{id}', 'SectionController@editSectionView')->name('admin_edit_section_view')->middleware('auth:admin');

//edit category view
Route::get('admin/edit/category/{id}', 'CategoryController@editCategoryView')->name('admin_edit_category_view')->middleware('auth:admin');

//edit filter category view
Route::get('admin/edit/filter-category/{id}', 'FilterController@editFilterCategoryView')->name('admin_edit_filter_category_view')->middleware('auth:admin');

//edit subcategory view
Route::get('admin/edit/subcategory/{id}', 'SubcategoryController@editSubcategoryView')->name('admin_edit_subcategory_view')->middleware('auth:admin');

//edit filter subcategory view
Route::get('admin/edit/filter-subcategory/{id}', 'FilterSubcategoryController@editFilterSubcategoryView')->name('admin_edit_filter_subcategory_view')->middleware('auth:admin');

//edit fashion view
Route::get('admin/edit/fashion/{id}', 'FashionController@editFashionView')->name('admin_edit_fashion_view')->middleware('auth:admin');


//post edit product
Route::post('admin/post/edit/product/{id}', 'Product@postEditProduct')->name('post_edit_product')->middleware('auth:admin');

//delete product
Route::post('admin/post/delete/product/{id}', 'Product@deleteProduct')->name('delete_product')->middleware('auth:admin');

//post edit brand
Route::post('admin/post/edit/brand/{id}', 'BrandController@postEditBrand')->name('post_edit_brand')->middleware('auth:admin');

//delete brand
Route::post('admin/post/delete/brand/{id}', 'BrandController@deleteBrand')->name('delete_brand')->middleware('auth:admin');

//post edit slider
Route::post('admin/post/edit/slider/{id}', 'SliderController@postEditSlider')->name('post_edit_slider')->middleware('auth:admin');

//delete slider
Route::post('admin/post/delete/slider/{id}', 'SliderController@deleteSlider')->name('delete_slider')->middleware('auth:admin');

//post edit section
Route::post('admin/post/edit/section/{id}', 'SectionController@postEditSection')->name('post_edit_section')->middleware('auth:admin');

//delete section
Route::post('admin/post/delete/section/{id}', 'SectionController@deleteSection')->name('delete_section')->middleware('auth:admin');

//post edit category
Route::post('admin/post/edit/category/{id}', 'CategoryController@postEditCategory')->name('post_edit_category')->middleware('auth:admin');

//delete category
Route::post('admin/post/delete/category/{id}', 'CategoryController@deleteCategory')->name('delete_category')->middleware('auth:admin');

//post edit filter category
Route::post('admin/post/edit/filter-category/{id}', 'FilterController@postEditFilterCategory')->name('post_edit_filter_category')->middleware('auth:admin');

//delete filter category
Route::post('admin/post/delete/filter-category/{id}', 'FilterController@deleteFilterCategory')->name('delete_filter_category')->middleware('auth:admin');

//post edit filter subcategory
Route::post('admin/post/edit/filter-subcategory/{id}', 'FilterSubcategoryController@postEditFilterSubcategory')->name('post_edit_filter_subcategory')->middleware('auth:admin');

//delete filter subcategory
Route::post('admin/post/delete/filter-subcategory/{id}', 'FilterSubcategoryController@deleteFilterSubcategory')->name('delete_filter_subcategory')->middleware('auth:admin');

//post edit subcategory
Route::post('admin/post/edit/subcategory/{id}', 'SubcategoryController@postEditSubcategory')->name('post_edit_subcategory')->middleware('auth:admin');

//delete subcategory
Route::post('admin/post/delete/subcategory/{id}', 'SubcategoryController@deleteSubcategory')->name('delete_subcategory')->middleware('auth:admin');

//post edit fashion
Route::post('admin/post/edit/fashion/{id}', 'FashionController@postEditFashion')->name('post_edit_fashion')->middleware('auth:admin');

//delete fashion
Route::post('admin/post/delete/fashion/{id}', 'FashionController@deleteFashion')->name('delete_fashion')->middleware('auth:admin');

//admin home View
Route::get('/let/ad/hp/tk/plc', 'AdminAuth\AdminLoginController@getAdminHomeView')->name('admin_home_view')->middleware('auth:admin');

//admin registration view
Route::get('/let/ad/reg/tk/plc', 'AdminAuth\AdminRegistrationController@getRegistrationView')->name('admin_registration_view')
    ->middleware('guest:admin');

//admin login view
Route::get('/let/ad/lgn/tk/plc', 'AdminAuth\AdminLoginController@getLoginView')->name('admin_login_view')->middleware('guest:admin');

//admin post registration
Route::post('/let/post/ad/reg/tk/plc', 'AdminAuth\AdminRegistrationController@registerAsAdmin')->name('post_admin_registration');

//admin login authentication
Route::post('/let/post/ad/lgn/tk/plc', 'AdminAuth\AdminLoginController@authenticateAdmin')->name('post_admin_login');

//admin logout authentication
Route::get('let/ad/lgout/tk/plc', 'AdminAuth\AdminLoginController@logoutAdmin')->name('admin_logout');

//edit admin profile view
Route::get('/let/ad/pfl/tk/plc/{id}', 'AdminAuth\AdminController@getAdminProfileView')
    ->name('admin_profile_view')->middleware('auth:admin');

//post edit admin profile
Route::post('/let/pst/edt/ad/pfl/tk/plc/{id}', 'AdminAuth\AdminController@postEditProfile')
    ->name('post_edit_admin_profile')->middleware('auth:admin');

//admin forgot password view
Route::get('/let/ad/fgt/pw/tk/plc', 'AdminAuth\AdminForgotPasswordController@getForgotPasswordview')->name('admin_forgot_password_view');

//admin change password view
Route::get('/let/ad/cng/pw/tk/plc/{email}', 'AdminAuth\AdminForgotPasswordController@getChangePasswordview')->name('admin_change_password_view')->middleware('signed');

//post admin forgot password
Route::post('/let/ad/post/fgt/pw/tk/plc', 'AdminAuth\AdminForgotPasswordController@emailForgotPassword')->name('post_admin_email_forgot_password');


//post admin change password
Route::post('/let/ad/post/cng/pw/tk/plc', 'AdminAuth\AdminForgotPasswordController@changeForgotPassword')->name('post_admin_change_password');


//testing
Route::get('/once/check', function () {
//    $products = Product::where('is_email_send',0)->get();
//    $subscribers = \App\Newsletter::all();
//    foreach ($products as $product) {
////        return $product->title;
//        foreach ($subscribers as $subscriber) {
////            return $subscriber->email;
//            Mail::to($subscriber->email)->send(new newsletter($product));
//        }
//    }
    $product = Product::find(21);
    Mail::to('muhammadwaqar6868@gmail.com')->send(new newsletter());

});

Route::get('send/mail', function () {
    return Mail::to('muhammadwaqar6868@gmail.com')->send(new ForgotPassword());
});
Route::get('/once/image', function () {
    return storage_path();
});
Route::get('/load/imageview', function () {
    return view('upload');
});
Route::post('/upload/image', function (\Illuminate\Http\Request $request) {
    $file = $request->file('myimage');
    $file->store('public/images');
//    dd($file);
    $image = Image::make($request->file('myimage'));
    $image->resize('300', '200', function ($constraint) {
        $constraint->aspectRatio();
    })->save(storage_path('/app/public/images/') . 'resize_' . $file->hashName());
//    dd($image);
//    $file->storeAs('public/images','resize_'.$file->hashName());
    if (File::isDirectory('sniper')) {

    } else {
        File::makeDirectory(storage_path('app/public/images/'));
    }

    dd($file);
    return $image;
});
