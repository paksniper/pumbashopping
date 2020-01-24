<?php


namespace App\Http\View\Composers;


use App\Brand;
use App\Category;
use App\Experiment;
use App\Fashion;
use App\Product;
use App\Slider;
use App\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeComposer
{

    public function __construct()
    {
    }


    public function compose(View $view)
    {
        $admin = false;
        $main_categories = Fashion::all();
        $sliders = Slider::all();
        $slider_section = Slider::distinct()->pluck('section');
        $sections = Product::whereNotIn('section',$slider_section)->distinct()->pluck('section');
        $brands = Brand::all();
        $products = null;
        $main_categories = Fashion::all();
        $result = new Collection();
        foreach ($sections as $section) {
            $products = Product::where('section', $section)->get();
            $result->push($products);

        }
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
        }

        $category_html = '';
//        if($main_categories->isEmpty()) {
//            $category_html = "<li><a href=''>No fashion to display</a></li>";
//        } else {
//            foreach ($main_categories as $fashion) {
//                $category_html .= "<li><a href='".route('view_fashion_products',Str::slug($fashion->fashion,'-'))."'>$fashion->fashion</a><ul>";
//                $categories = Category::where('fashion', $fashion->fashion)->get();
//                if ($categories->isEmpty()) {
//                    $category_html .= "<li><a href='#'>No category to display</a></li>";
//                } else {
//                    foreach ($categories as $category) {
//                        $category_html .= "<li><a href='".route('view_category_products',Str::slug($category->category,'-'))."'>$category->category</a><ul>";
//                        $subcategories = Subcategory::where('category', $category->category)->get();
//                        if ($subcategories->isEmpty()) {
//                            $category_html .= "<li><a href=''>No subcategory to show</a></li>";
//                        } else {
//                            foreach ($subcategories as $subcategory) {
//                                $category_html .= "<li><a href='".route('view_subcategory_products',['subcategory'=>Str::slug($subcategory->subcategory,'-'),'category'=>Str::slug($category->category,'-')])."'>$subcategory->subcategory</a></li>";
//                            }
//                        }
//                        $category_html .= "</ul>";
//                    }
//                }
//                $category_html .= "</ul></li>";
//            }
//        }

        if ($main_categories->isEmpty()) {
            $category_html = "<li><a href=''>No fashion to display</a></li>";
        } else {
            foreach ($main_categories as $fashion) {
                $category_html .= "<li><a href='" . route('view_fashion_products', Str::slug($fashion->fashion, '-')) . "'>$fashion->fashion</a><ul>";
                $categories = Product::where('fashion', $fashion->fashion)->distinct()->pluck('category');
                if ($categories->isEmpty()) {
                    $category_html .= "<li><a href='#'>No category to display</a></li>";
                } else {
                    foreach ($categories as $category) {
                        $category_html .= "<li><a href='" . route('view_category_products', Str::slug($category, '-')) . "'>$category</a><ul>";
                        $subcategories = Product::where('category', $category)->distinct()->pluck('subcategory');
                        if ($subcategories->isEmpty()) {
                            $category_html .= "<li><a href=''>No subcategory to show</a></li>";
                        } else {
                            foreach ($subcategories as $subcategory) {
                                $category_html .= "<li><a href='" . route('view_subcategory_products', ['subcategory' => Str::slug($subcategory, '-'), 'category' => Str::slug($category, '-')]) . "'>$subcategory</a></li>";
                            }
                        }
                        $category_html .= "</ul>";
                    }
                }
                $category_html .= "</ul></li>";
            }
        }


        $view->with([
            'products' => $result,
            'sections' => $sections,
            'categories' => $main_categories,
            'sliders' => $sliders,
            'html' => $category_html,
            'admin' => $admin,
            'feature_brands' => $brands,
            'feature_brands_title' => 'feature brand'
        ]);

    }
}
