<?php


namespace App\Http\View\Composers;


use App\Brand;
use App\Category;
use App\Fashion;
use App\Product;
use App\Section;
use App\Slider;
use App\Subcategory;
use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminHomeComposer
{
    public function compose(View $view)
    {
        $products = Product::all();
        $product_counts = $products->count();
        $section_counts = Section::all()->count();
        $category_counts = Category::all()->count();
        $brand_counts = Brand::all()->count();
        $slider_counts = Slider::all()->count();
        $fashion_counts = Fashion::all()->count();
        $main_categories = Fashion::all();
//        $categories = Category::all();
//        $subcategories = Subcategory::all();
        $admin = null;
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

        $view->with(
            [
                'products' => $products, 'product_count' => $product_counts,
                'section_count' => $section_counts, 'category_count' => $category_counts,
                'brand_count' => $brand_counts, 'slider_count' => $slider_counts,
                'fashion_count' => $fashion_counts, 'fashions' => $main_categories,
                'admin' => $admin, 'html' => $category_html
            ]
        );

    }
}
