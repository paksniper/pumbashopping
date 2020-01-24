<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Feature;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductDetail extends Controller
{
    public function __construct()
    {
    }

    public function displayProduct($title) {
        $title = Str::slug($title,' ');
        $product = Product::where('title',$title)->first();
        $brand = Brand::where('title',$product->brand)->first();
        $products_you_may_like = Product::inRandomOrder()->take(10)->get();
//        dd($brand);

        return view('project_rest_layout.product_detail',
            [
                'product' => $product,
                'brand' => $brand,
                'product_you_may_like' => $products_you_may_like,
                'product_you_may_like_title' => 'Products You May Like'
            ]);
    }


}
