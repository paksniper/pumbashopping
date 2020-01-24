<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Filtercategory;
use App\Filtersubcategory;
use App\Product;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use JavaScript;


class View_all_products extends Controller
{
    public function viewProducts($product_to_view)
    {
        $product_to_view = Str::slug($product_to_view, ' ');
        $products = Product::where('section', $product_to_view)->paginate(20);
        $counts = Product::where('section', $product_to_view)->count();
        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'count' => $counts]);
    }

    public function viewFashionProducts($fashion)
    {
        $fashion = Str::slug($fashion, ' ');
        $brands = new Collection();
        $brand_counter = 0;
        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('fashion', $fashion)->min('price');
        $max_price = Product::where('fashion', $fashion)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $category) {
            $mycategory = str_replace(' ', '_', $category->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('fashion', $fashion)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container" style="width: 200px;">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }
        $categories = Product::where('fashion', $fashion)->distinct()->pluck('category');
        $product_brands = Product::where('fashion', $fashion)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $brand = Brand::where('title', $brand)->get();
            $brands->push($brand);
            $brand_counter++;
        }
//        dd($brands);
        $products = Product::where('fashion', $fashion)->paginate(20);
        $counts = Product::where('fashion', $fashion)->count();
        $best_of_category = new Collection();
        $categories_related_to_fashion = Product::where('fashion', $fashion)->distinct()->pluck('category');
        foreach ($categories_related_to_fashion as $category) {
            $best_of_category->push(Product::where('category', $category)->where('percentage', Product::where('category', $category)->max('percentage'))->first());
        }
        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts, 'fp' => $fashion, 'fashionCategories' => $categories,
                'product_brands' => $brands, 'brand_counter' => $brand_counter, 'filter_html' => $filter_html,
                'min_price' => $min_price, 'max_price' => $max_price, 'container_id' => 'fashion', 'container_value' => $fashion,
                'fashion_value' => 'category', 'best_of_category_title' => 'Best Of Category']);
    }

    public function viewAjaxFilterProducts(Request $request)
    {
        $filters = $request->input('search');
        $table_columns = Schema::getColumnListing('products');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "category" && $filters[$key][0] !== "price" && $filters[$key][0] !== "fashion" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }

//        foreach ($filters as $k => $v) {
//            if($filters[$k][0] === 'category') {
//                $min_price = Product::whereIn($filters[$k][0],$filters[$k][1])->min('price');
//                $max_price = Product::whereIn($filters[$k][0],$filters[$k][1])->max('price');
//            }
//        }

        $products = Product::where(function ($query) use ($filters, $request) {
            $fashion = $request->input('fashion');
            $is_fashion = false;
            $is_category = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('fashion', $fashion)->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'fashion') {
                        foreach ($filters as $k => $v) {
                            if ($filters[$k][0] === "price") {
                                $query->where('fashion', $fashion)->whereBetween($filters[$k][0], $filters[$k][1]);
                            }
                        }
                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'category') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                        $is_category = true;
                    } else if ($is_category === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('fashion', $fashion);
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->paginate(20);


        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        $is_fashion_available = false;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "category") || $filters[$key][0] === "fashion" || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "category") {
                        if ($is_fashion_available === true) {
                            break;
                        } else {
                            $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                                ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        }

                    } else if ($filters[$key][0] === "fashion") {
                        $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        $is_fashion_available = true;

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('fashion', $request->input('fashion'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'fashion' => $request->input('fashion'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

    public function viewCategoryProducts($category)
    {


        $brands = new Collection();
        $brand_counter = 0;
        $category = Str::slug($category, ' ');
        $categoryFashion = Product::where('category', $category)->first();
        $subcategories = Product::where('category', $category)->distinct()->pluck('subcategory');
        $products = Product::where('category', $category)->paginate(20);
        $counts = Product::where('category', $category)->count();

        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('category', $category)->min('price');
        $max_price = Product::where('category', $category)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $cat) {
            $mycategory = str_replace(' ', '_', $cat->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('category', $category)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $cat->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                    
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                   
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }

        $product_brands = Product::where('category', $category)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }

        $category_fashion = Product::where('category', $category)->first();
        $best_of_category = new Collection();
        $categories_related_to_fashion = Product::where('fashion', $category_fashion->fashion)->distinct()->pluck('category');
        foreach ($categories_related_to_fashion as $cat) {
            $best_of_category->push(Product::where('category', $cat)->where('percentage', Product::where('category', $cat)->max('percentage'))->first());
        }

        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts,
                'cp' => $category, 'catSubCategories' => $subcategories,
                'categoryFashion' => $categoryFashion, 'product_brands' => $brands,
                'brand_counter' => $brand_counter, 'min_price' => $min_price,
                'max_price' => $max_price, 'filter_html' => $filter_html, 'container_id' => 'category',
                'container_value' => $category, 'fashion_value' => 'subcategory',
                'best_of_category_title' => 'Best Of Category']);

    }

    public function viewAjaxCategoryProducts(Request $request)
    {
        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "category" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }


        $products = Product::where(function ($query) use ($filters, $request) {
            $category = $request->input('category');
            $is_subcategory = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('category', $category)->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'category') {
                        $query->where('category', $category);
//                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'subcategory') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('category',$category);
                        $is_subcategory = true;
                    } else if ($is_subcategory === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('category', $category);
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->get();

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('category', $request->input('category'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                    
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'category' => $request->input('category'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

    public function viewSubcategoryProducts($subcategory, $category)
    {

        $brands = new Collection();
        $brand_counter = 0;
        $category = Str::slug($category, ' ');
        $subcategory = Str::slug($subcategory, ' ');
        $categoryFashion = Product::where('category', $category)->first();
        $subcategories = Product::where('category', $category)->distinct()->pluck('subcategory');
        $products = Product::where('category',$category)->where('subcategory', $subcategory)->paginate(20);
        $counts = Product::where('category',$category)->where('subcategory', $subcategory)->count();

        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('category',$category)->where('subcategory', $subcategory)->min('price');
        $max_price = Product::where('category',$category)->where('subcategory', $subcategory)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $cat) {
            $mycategory = str_replace(' ', '_', $cat->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('category',$category)->where('subcategory', $subcategory)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $cat->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }

        $product_brands = Product::where('subcategory', $subcategory)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }

        $category_fashion = Product::where('category', $category)->first();
        $best_of_category = new Collection();
        $categories_related_to_fashion = Product::where('fashion', $category_fashion->fashion)->distinct()->pluck('category');
        foreach ($categories_related_to_fashion as $cat) {
            $best_of_category->push(Product::where('category', $cat)->where('percentage', Product::where('category', $cat)->max('percentage'))->first());
        }

        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts,
                'scp' => $subcategory, 'categoryForSubcategory' => $category,
                'categoryFashion' => $categoryFashion, 'product_brands' => $brands,
                'brand_counter' => $brand_counter, 'min_price' => $min_price,
                'max_price' => $max_price, 'filter_html' => $filter_html, 'container_id' => 'subcategory',
                'container_value' => $category, 'fashion_value' => 'subcategory',
                'best_of_category_title' => 'Best Of Category']);

    }

    public function viewAjaxSubcategoryProducts(Request $request)
    {
        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }


        $products = Product::where(function ($query) use ($filters, $request) {
            $category = $request->input('category');
            $is_subcategory = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            foreach ($filters as $key => $value) {
                if ($filters[$key][0] === 'price') {
                    $q = $query->whereBetween($filters[$key][0], $filters[$key][1])->where('category', $category);

                } else if ($filters[$key][0] === 'subcategory') {
                    $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    $is_subcategory = true;
                } else {
                    $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('category', $category);
                }
                if ($i < 1) {
                    $myquery = $q;
                } else {
                    $myquery->union($q);
                }
                $i++;
            }
        })->get();

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                            ->where('category',$request->input('category'))->whereNotNull($mycategory)->distinct()->pluck($mycategory);

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('category', $request->input('category'))
                            ->where('subcategory',$request->input('subcategory'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'subcategory' => $request->input('subcategory'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

//    public function viewSectionProducts($section)
//    {
//        $brands = new Collection();
//        $brand_counter = 0;
//        $section = Str::slug($section, ' ');
//        $products = Product::where('section', $section)->paginate(20);
//        $counts = Product::where('section', $section)->count();
//        $sections = Product::where('section', $section)->distinct()->pluck('section');
//        $categories = Product::where('section', $section)->distinct()->pluck('category');
////        $fashions = Product::where('section', $section)->distinct()->pluck('fashion');
//        $subcategories = Product::where('section', $section)->distinct()->pluck('subcategory');
//        $product_brands = Product::where('section', $section)->distinct()->pluck('brand');
//        foreach ($product_brands as $brand) {
//            $br = Brand::where('title', $brand)->get();
//            $brands->push($br);
//            $brand_counter++;
//        }
//        return view('project_rest_layout.view_all_products')
//            ->with(['viewproducts' => $products, 'count' => $counts, 'sp' => $section, 'ss' => $sections,
//                'cts' => $categories, 'scs' => $subcategories, 'product_brands' => $brands,
//                'brand_counter' => $brand_counter]);
//    }

    public function viewSectionProducts($section)
    {
        $brands = new Collection();
        $brand_counter = 0;
        $section = Str::slug($section, ' ');
        $products = Product::where('section', $section)->paginate(20);
        $counts = Product::where('section', $section)->count();
        $categories = Product::where('section', $section)->distinct()->pluck('category');
        $subcategories = Product::where('section', $section)->distinct()->pluck('subcategory');
        $product_brands = Product::where('section', $section)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }


        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('section', $section)->min('price');
        $max_price = Product::where('section', $section)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $category) {
            $mycategory = str_replace(' ', '_', $category->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('section', $section)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container" style="width: 200px;">' . $filter . '</span>
                                                   
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }

        $best_of_category = new Collection();
        $categories_related_to_section = Product::where('section', $section)->distinct()->pluck('category');
        foreach ($categories_related_to_section as $category) {
            $best_of_category->push(Product::where('category', $category)->where('percentage', Product::where('category', $category)->max('percentage'))->first());
        }
        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts, 'sp' => $section, 'sectionCategories' => $categories,
                'sectionSubcategories' => $subcategories, 'product_brands' => $brands, 'brand_counter' => $brand_counter, 'filter_html' => $filter_html,
                'min_price' => $min_price, 'max_price' => $max_price, 'section' => $section, 'container_id' => 'section', 'container_value' => $section,
                'fashion_value' => 'subcategory','best_of_category_title' => 'Best Of Category']);
    }

    public function viewAjaxSectionProducts(Request $request)
    {

        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "section" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }

//        foreach ($filters as $k => $v) {
//            if($filters[$k][0] === 'category') {
//                $min_price = Product::whereIn($filters[$k][0],$filters[$k][1])->min('price');
//                $max_price = Product::whereIn($filters[$k][0],$filters[$k][1])->max('price');
//            }
//        }

        $products = Product::where(function ($query) use ($filters, $request) {
            $section = $request->input('section');
            $is_fashion = false;
            $is_category = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('section', $section)->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'section') {
                        foreach ($filters as $k => $v) {
                            if ($filters[$k][0] === "price") {
                                $query->where('section', $section)->whereBetween($filters[$k][0], $filters[$k][1]);
                            }
                        }
//                        $q = $query->whereIn('section',$section);
                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'subcategory') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                        $is_category = true;
                    } else if ($is_category === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('section', $section);
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->paginate(20);

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        $is_section_available = false;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || $filters[$key][0] === "section" || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        if ($is_section_available === true) {
                            break;
                        } else {
                            $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                                ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        }

                    } else if ($filters[$key][0] === "section") {
                        $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        $is_section_available = true;

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('section', $request->input('section'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'section' => $request->input('section'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

    public function viewBrandProducts($brand)
    {

        $brands = new Collection();
        $brand_counter = 0;
        $brand = Str::slug($brand, ' ');
        $products = Product::where('brand', $brand)->paginate(20);
        $counts = Product::where('brand', $brand)->count();
        $categories = Product::where('brand', $brand)->distinct()->pluck('category');
        $subcategories = Product::where('brand', $brand)->distinct()->pluck('subcategory');
        $product_brands = Product::where('brand', $brand)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }


        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('brand', $brand)->min('price');
        $max_price = Product::where('brand', $brand)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $category) {
            $mycategory = str_replace(' ', '_', $category->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('brand', $brand)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container" style="width: 200px;">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }

        $best_of_category = new Collection();
        $min_brand_price = Product::where('brand',$brand)->max('percentage');
        $best_of_category = Product::where('brand',$brand)->where('percentage' ,'<',$min_brand_price)->take(10)->get();

        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts, 'pb' => $brand, 'brandCategories' => $categories,
                'brandSubcategories' => $subcategories, 'product_brands' => $brands, 'brand_counter' => $brand_counter, 'filter_html' => $filter_html,
                'min_price' => $min_price, 'max_price' => $max_price, 'brand' => $brand, 'container_id' => 'brandz', 'container_value' => $brand,
                'fashion_value' => 'subcategory','best_of_category_title' => 'Best Of Brand']);
    }

    public function viewAjaxBrandProducts(Request $request)
    {

        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "brandz" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }

//        foreach ($filters as $k => $v) {
//            if($filters[$k][0] === 'category') {
//                $min_price = Product::whereIn($filters[$k][0],$filters[$k][1])->min('price');
//                $max_price = Product::whereIn($filters[$k][0],$filters[$k][1])->max('price');
//            }
//        }

        $products = Product::where(function ($query) use ($filters, $request) {
            $brand = $request->input('brand');
            $is_fashion = false;
            $is_subcategory = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('brand', $brand)->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'brandz') {
                        foreach ($filters as $k => $v) {
                            if ($filters[$k][0] === "price") {
                                $query->where('brand', $brand)->whereBetween($filters[$k][0], $filters[$k][1]);
                            }
                        }
//                        $q = $query->whereIn('brand',$brand);
                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'subcategory') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                        $is_subcategory = true;
                    } else if ($is_subcategory === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('brand', $brand);
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->paginate(20);

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        $is_brandz_available = false;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || $filters[$key][0] === "brandz" || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        if ($is_brandz_available === true) {
                            break;
                        } else {
                            $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                                ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        }

                    } else if ($filters[$key][0] === "brandz") {
                        $filters_category = Product::whereIn('brand', $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        $is_brandz_available = true;

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('brand', $request->input('brand'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                  
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                   
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'brand' => $request->input('brand'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

    public function viewTraderProducts($trader)
    {

        $brands = new Collection();
        $brand_counter = 0;
        $trader = Str::slug($trader, ' ');
        $products = Product::where('trader', $trader)->paginate(20);
        $counts = Product::where('trader', $trader)->count();
        $categories = Product::where('trader', $trader)->distinct()->pluck('category');
        $subcategories = Product::where('trader', $trader)->distinct()->pluck('subcategory');
        $product_brands = Product::where('trader', $trader)->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }


        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('trader', $trader)->min('price');
        $max_price = Product::where('trader', $trader)->max('price');
        JavaScript::put([
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);
//        dd($min_price." and max price is ".$max_price);
        $filter_categories = Filtercategory::all();
        foreach ($filter_categories as $category) {
            $mycategory = str_replace(' ', '_', $category->category);
//            dd(str_replace(' ','_',$category->category));
            array_push($filter_container, $mycategory);
            $filters = Product::where('trader', $trader)->whereNotNull($mycategory)->distinct()->pluck($mycategory);
            JavaScript::put([
                'filter_holders' => $filter_container
            ]);

            if (!$filters->isEmpty()) {
                $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
            }
            foreach ($filters as $filter) {
                $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                   
                                            <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container" style="width: 200px;">' . $filter . '</span>
                                                   
                                                </label>
                                            </div>
                                        </li>';
            }
            if (!$filters->isEmpty()) {
                $filter_html .= ' </ul>
                        </div>
                    </div>';
            }

        }

        $best_of_category = new Collection();
        $min_trader_price = Product::where('trader',$trader)->max('percentage');
        $best_of_category = Product::where('trader',$trader)->where('percentage' ,'<',$min_trader_price)->take(10)->get();

        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $products, 'best_of_category' => $best_of_category, 'count' => $counts, 'tp' => $trader, 'traderCategories' => $categories,
                'traderSubcategories' => $subcategories, 'product_brands' => $brands, 'brand_counter' => $brand_counter, 'filter_html' => $filter_html,
                'min_price' => $min_price, 'max_price' => $max_price, 'trader' => $trader, 'container_id' => 'traderz', 'container_value' => $trader,
                'fashion_value' => 'subcategory','best_of_category_title' => 'Best Of Trader']);
    }

    public function viewAjaxTraderProducts(Request $request)
    {

        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "traderz" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }

//        foreach ($filters as $k => $v) {
//            if($filters[$k][0] === 'category') {
//                $min_price = Product::whereIn($filters[$k][0],$filters[$k][1])->min('price');
//                $max_price = Product::whereIn($filters[$k][0],$filters[$k][1])->max('price');
//            }
//        }

        $products = Product::where(function ($query) use ($filters, $request) {
            $trader = $request->input('trader');
            $is_fashion = false;
            $is_subcategory = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('trader', $trader)->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'traderz') {
                        foreach ($filters as $k => $v) {
                            if ($filters[$k][0] === "price") {
                                $query->where('trader', $trader)->whereBetween($filters[$k][0], $filters[$k][1]);
                            }
                        }
//                        $q = $query->whereIn('brand',$brand);
                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'subcategory') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                        $is_subcategory = true;
                    } else if ($is_subcategory === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('trader', $trader);
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->paginate(20);

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        $is_brandz_available = false;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || $filters[$key][0] === "traderz" || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        if ($is_brandz_available === true) {
                            break;
                        } else {
                            $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                                ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        }

                    } else if ($filters[$key][0] === "traderz") {
                        $filters_category = Product::whereIn('trader', $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        $is_brandz_available = true;

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('trader', $request->input('trader'))
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                    
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'trader' => $request->input('trader'),
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

    public function viewAjaxSearchProducts(Request $request)
    {

        $filters = $request->input('search');
        $html = null;
        $discount_html = null;
        $price_html = null;
        $percentage_html = null;
        $filter_product = null;
        $product_filters = null;
        $filter_html = false;
        $min_price = null;
        $max_price = null;
        $checked_category = [];
        $price_array = [];

        foreach ($filters as $key => $value) {
            if ($filters[$key][0] !== "subcategory" && $filters[$key][0] !== "price" && $filters[$key][0] !== "brand") {
                foreach ($filters[$key][1] as $category) {
                    array_push($checked_category, str_replace(' ', '_', $category));
                }
            }
        }

//        foreach ($filters as $k => $v) {
//            if($filters[$k][0] === 'category') {
//                $min_price = Product::whereIn($filters[$k][0],$filters[$k][1])->min('price');
//                $max_price = Product::whereIn($filters[$k][0],$filters[$k][1])->max('price');
//            }
//        }

        $products = Product::where(function ($query) use ($filters, $request) {
            $search = $request->input('searchz');
            $is_fashion = false;
            $is_subcategory = false;
            $i = 0;
            $myquery = null;
            $q = null;
            $filters_length = count($filters);
            if ($filters_length === 1) {
                $query->where('title', 'LIKE', "%{$request->input('searchz')}%")->whereBetween($filters[0][0], $filters[0][1]);
            } else {
                foreach ($filters as $key => $value) {
                    if ($filters[$key][0] === 'price') {
                        $q = $query->whereBetween($filters[$key][0], $filters[$key][1]);

                    } else if ($filters[$key][0] === 'traderz') {
                        foreach ($filters as $k => $v) {
                            if ($filters[$k][0] === "price") {
                                $query->where('title', 'LIKE', "%{$request->input('searchz')}%")->whereBetween($filters[$k][0], $filters[$k][1]);
                            }
                        }
//                        $q = $query->whereIn('brand',$brand);
                        $is_fashion = true;
                        break;
                    } else if ($filters[$key][0] === 'subcategory') {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                        $is_subcategory = true;
                    } else if ($is_subcategory === false) {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1])->where('title', 'LIKE', "%{$request->input('searchz')}%");
                    } else {
                        $q = $query->whereIn($filters[$key][0], $filters[$key][1]);
                    }
                    if ($i < 1) {
                        $myquery = $q;
                    } else {
                        $myquery->union($q);
                    }
                    $i++;
                }
            }
        })->paginate(20);

        foreach ($products as $product) {
            if ($product->discount != "") {
                $discount_html = '<span class="text-success">Rs.&nbsp;' . $product->discount . '</span>';
                $price_html = '&nbsp;<s class="text-muted"><small>Rs.&nbsp;' . $product->price . '</small></s>';
                $percentage_html = '&nbsp;<span class="text-danger">' . $product->percentage . '% Off</span>';
            } else {
                $price_html = '&nbsp;<span class="text-success">Rs.&nbsp;' . $product->price . '</span>';
            }

            $html .= '<div class="col-md-3 px-1 product-on-view">
                                        <div class="card border-0 rounded-0 product-card">
                                            <a href="' . route('product_detail', Str::slug('' . $product->title . '', '-')) . '">
                                                <img class="img-fluid card-img-top"  src="' . asset('storage/images/' .
                    Str::slug($product->fashion, '-') . '/' . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image) . '">
                                            </a>
                                        <div class="card-body px-2 text-center">
                                            <p class="text-truncate view-product-title">
                                                <a href="" class="text-dark" style="text-decoration: none;">' . $product->title . '</a>
                                            </p>
                                            <p class="view-product-price-wrapper">
                                                ' . $discount_html . '' . $price_html . '' . $percentage_html . '
                                            </p>
                                        </div>
                                    </div>

                                    </div>';
            $percentage_html = null;
            $price_html = null;
            $discount_html = null;

        }

//        $min_price = Product::whereIn('category',$filters[0][1])->min('price');
//        $max_price = Product::whereIn('category',$filters[0][1])->max('price');

        $filters_category = null;
        $is_searchz_available = false;
        foreach ($filters as $key => $value) {
            if (($filters[$key][0] === "subcategory") || ($filters[$key][0] === "price" && count($filters) === 1)) {
                $filter_categories = Filtercategory::all();
                foreach ($filter_categories as $category) {
                    $mycategory = str_replace(' ', '_', $category->category);

                    if ($filters[$key][0] === "subcategory") {
                        if ($is_searchz_available === true) {
                            break;
                        } else {
                            $filters_category = Product::whereIn($filters[$key][0], $filters[$key][1])
                                ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        }

                    } else if ($filters[$key][0] === "traderz") {
                        $filters_category = Product::whereIn('trader', $filters[$key][1])
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                        $is_searchz_available = true;

                    } else if (count($filters) === 1 && $filters[$key][0] === "price") {
                        $filters_category = Product::where('title', 'LIKE', "%{$request->input('searchz')}%")
                            ->whereNotNull($mycategory)->distinct()->pluck($mycategory);
                    }

                    if (!$filters_category->isEmpty()) {
                        $filter_html .= '<div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom">
                        <div class="card-header border-bottom border-top bg-white collapse_' . $mycategory . '" style="cursor:pointer;" onclick="filter(\'' . $mycategory . '\')">
                            <div class="card-text float-left" style="font-size: 15px;">' . $category->category . '</div>
                            <span class="float-right fas fa-angle-up brand-slide-icon mt-1"></span>
                        </div>
                        <div class="card-body ' . $mycategory . '_container p-1">
                            <ul class="list-unstyled brands mCustomScrollbar" id="' . $mycategory . '" data-mcs-theme="dark">';
                    }
                    foreach ($filters_category as $filter) {
                        $filter_html .= ' <li class="d-block py-2 align-items-center px-2 subcategory-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="' . $filter . '" title="' . Str::slug($filter, '_') . '">
                                                <label class="custom-control-label" for="' . $filter . '">
                                                    
                                                <span
                                                class="mb-1 d-inline-block text-truncate subcategory-item-container">' . $filter . '</span>
                                                    
                                                </label>
                                            </div>
                                        </li>';
                    }
                    if (!$filters_category->isEmpty()) {
                        $filter_html .= ' </ul>
                        </div>
                    </div>';
                    }
                }
            }
        }


        return response()->json(['result' => $html, 'search' => 'Search Product',
            'count' => count($products), 'filter_html' => $filter_html, 'min_price' => $min_price,
            'max_price' => $max_price, 'checked_categories' => $checked_category, 'price_array' => $price_array]);

    }

}
