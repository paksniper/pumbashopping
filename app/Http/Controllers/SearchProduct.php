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
use Illuminate\Support\Str;
use JavaScript;

class SearchProduct extends Controller
{
    public function __construct()
    {
    }

    public function searchForProduct(Request $request)
    {
        $result = '';
        $search_products = null;
        $category = Str::slug($request->input('category'), ' ');
        if ($category == "all") {
            $search_products = Product::where('title', 'LIKE', '%' . $request->search . '%')
                ->take(10)->inRandomOrder()->get();
        } else {
            $search_products = Product::where('title', 'LIKE', '%' . $request->search . '%')
                ->where('fashion', $category)->take(10)->inRandomOrder()->get();
        }

        if ($search_products) {
            foreach ($search_products as $product) {
                $result .= '<a class="search-result-products" href="' . route('product_detail', Str::slug($product->title)) . '"><li class="m-2 my-0 d-flex align-items-center">' . $product->title . '</li></a><hr class="mx-0 my-2">';
            }
            return response()->json(['result' => $result]);
        }


    }

    public function searchSubmitProduct(Request $request)
    {

        $fashion = Str::slug($request->input('category'), ' ');
        $search_input = $request->input('search-input');
        $search_products = null;
        $counts = null;
        $categories = null;


        if ($fashion == 'all' && !empty($search_input)) {
            $search_products = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")
                ->paginate(20);
            $counts = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')
                ->count();
//            $category =Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')->distinct()->pluck('fashion');
            $categories = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')->distinct()->pluck('category');
            $subcategories = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')->distinct()->pluck('subcategory');


        } else if (!empty($search_input)) {
            $search_products = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")
                ->where('fashion', $fashion)->paginate(20);
            $counts = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')
                ->where('fashion', $fashion)->count();
//            $category =Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')->distinct()->pluck('fashion');
            $categories = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')->get();
            $subcategories = Product::where('title', 'LIKE', '%' . $request->input('search-input') . '%')
                ->where('fashion', $fashion)->distinct()->pluck('subcategory');

        } else if (empty($search_input)) {
            return redirect()->route('home');
//                ->with(['viewproducts' => null, 'best_of_category' => new Collection(), 'count' => 0, 'searchproduct' => 'Search Product', 'searchCategories' => $categories,
//                    'searchSubcategories' => null, 'product_brands' => null, 'brand_counter' => null, 'filter_html' => null,
//                    'min_price' => null, 'max_price' => null, 'searchinput' => null, 'container_id' => 'searchz', 'container_value' => null,
//                    'fashion_value' => 'subcategory','best_of_category_title' => 'Best Of Category']);
        }


        $brands = new Collection();
        $brand_counter = 0;
        $product_brands = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->distinct()->pluck('brand');
        foreach ($product_brands as $brand) {
            $br = Brand::where('title', $brand)->get();
            $brands->push($br);
            $brand_counter++;
        }


        $filter_html = null;
        $filter_container = [];
        $min_price = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->min('price');
        $max_price = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->max('price');
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
            $filters = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->whereNotNull($mycategory)->distinct()->pluck($mycategory);
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
                                                    <a href="#"
                                                       class="subcategory-item-container">
                                            <span
                                                class="mb-1 d-inline-block text-truncate" style="width: 200px;">' . $filter . '</span>
                                                    </a>
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
        if(!empty($search_input)) {
            if($search_products->isNotEmpty()) {
                $min_trader_price = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->max('percentage');
                $best_of_category = Product::where('title', 'LIKE', "%{$request->input('search-input')}%")->where('percentage', '<', $min_trader_price)->take(10)->get();
            }
        }
        return view('project_rest_layout.view_all_products')
            ->with(['viewproducts' => $search_products, 'best_of_category' => $best_of_category, 'count' => $counts, 'searchProduct' => 'Search Product', 'searchCategories' => $categories,
                'searchSubcategories' => $subcategories, 'product_brands' => $brands, 'brand_counter' => $brand_counter, 'filter_html' => $filter_html,
                'min_price' => $min_price, 'max_price' => $max_price, 'searchinput' => $search_input, 'container_id' => 'searchz', 'container_value' => $search_input,
                'fashion_value' => 'subcategory','best_of_category_title' => 'Best Of Category']);
    }
    public function searchForCategories(Request $request) {
        $result = null;
        $fashion  = $request->fashion;
        $categories = Category::where('fashion',$fashion)->get();
        foreach ($categories as $category) {
            $result.="<option value='$category->category'>$category->category</option>";
        }
        return response()->json(['result' => $result]);
    }

    public function searchForFilterCategories(Request $request) {
        $result = null;
        $fashion  = $request->fashion;
        $category = Filtercategory::where('fashion',$fashion)->get();
        foreach ($category as $cat) {
            $result.="<option value='$cat->category'>$cat->category</option>";
        }
        return response()->json(['result' => $result]);
    }

    public function searchForFilterSelection(Request $request) {
        $filter_category  = $request->filter_category;
        $result = "<div id='".Str::slug($filter_category,'_')."' class='col-md-2'><div class='form-group w-100'><label class='form-label'>Select ".$filter_category." </label><select name='".Str::slug($filter_category,'_')."' class='form-control'>";
        $subcategories = Filtersubcategory::where('category',$filter_category)->get();
        foreach ($subcategories as $subcategory) {
            $result.="<option value='".$subcategory->subcategory."'>$subcategory->subcategory</option>";
        }
        $result.="</select></div></div>";
        return response()->json(['result' => $result]);
    }

    public function searchForCategoriesList(Request $request) {
        $result = null;
        $fashion  = $request->fashion;
        $categories = Category::where('fashion',$fashion)->get();
        foreach ($categories as $category) {
            $result.="<li><a href='#'>$category->category</a><ul id='subcategories$category->id'><li>item1</li><li>item2</li></ul></li>";
        }
        return response()->json(['result' => $result]);
    }

    public function searchForSubcategories(Request $request) {
        $result = null;
        $category  = $request->category;
        $subcategories = Subcategory::where('category',$category)->get();
        foreach ($subcategories as $subcategory) {
            $result.="<option value='$subcategory->subcategory'>$subcategory->subcategory</option>";
        }
        return response()->json(['result' => $result]);
    }
}
