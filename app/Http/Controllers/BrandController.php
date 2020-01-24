<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function getBrandView()
    {
        $brands = Brand::all();

        return view('admin.brands')->with(
            [
                'brands' => $brands
            ]
        );

    }

    public function editBrandView($id)
    {
        $brand = Brand::where('id', $id)->first();
        return view('admin.edit_brands')->with('brand', $brand);

    }

    public function createBrand(Request $request)
    {
        $validateBrandData = $request->validate([
            'brand_title' => "required|min:3",
            'brand_image' => 'required|image|dimensions:min_width=162,min_height=100,max_width=162,max_height=100|mimes:jpeg'
        ]);

        $image = $request->file('brand_image');
        $image->store('public/images/brands/');

        Brand::create(['title' => $request->input('brand_title'), 'image' => $image->hashName(), now()]);

        return redirect(route('admin_brand_view'))->with(['status' => 'Brand successfully has been inserted']);
    }

    public function postEditBrand(Request $request, $id)
    {
        $validateBrandData = $request->validate([
            'brand_title' => "required|min:3",
            'brand_image' => 'nullable|image|dimensions:min_width=162,min_height=100,max_width=162,max_height=100|mimes:jpeg'
        ]);

        $original_title = $request->input('original_title');
        $title = $request->input('brand_title');
        $brand = Brand::where('id', $id)->first();
        $image = null;
        $new_image = $brand->image;
        if ($request->hasFile('brand_image')) {
            $image = $request->file('brand_image');
            File::delete(storage_path('app/public/images/brands/' . $brand->image));
            $image->store('public/images/brands/');
            if ($image->isValid()) {
                $new_image = $image->hashName();
            }
        }
        if ($original_title != $title) {
            $products = \App\Product::where('brand', $original_title)->get();
            foreach ($products as $product) {
                if ($product->brand == $original_title) {
                    $myproduct = \App\Product::find($product->id);
                    $myproduct->brand = $title;
                    $myproduct->save();
                }
            }
        }
        Brand::where('id', $id)->update(['title' => $request->input('brand_title'), 'image' => $new_image]);
        return redirect(route('admin_brand_view'))->with(['status' => 'Brand successfully has been updated']);
    }

    public function deleteBrand($id)
    {
        $brand = Brand::where('id', $id)->first();
        $products = \App\Product::where('brand', $brand->title)->get();
        foreach ($products as $product) {
            if ($product->brand == $brand->title) {
                File::delete(storage_path('app/public/images/' .
                    Str::slug($product->fashion, '-') . '/'
                    . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/' . $product->image));

                File::delete(storage_path('app/public/images/' .
                    Str::slug($product->fashion, '-') . '/'
                    . Str::slug($product->category, '-') . '/' .
                    Str::slug($product->subcategory, '-') . '/resize_' . $product->image));

                \App\Product::where('id', $product->id)->delete();
            }
        }
        if (file_exists(storage_path('app/public/images/brands/' . $brand->image))) {
            File::delete(storage_path('app/public/images/brands/' . $brand->image));
        }
        Brand::where('id', $id)->delete();
        return redirect(route('admin_brand_view'))->with(['status' => 'Brand successfully has been deleted']);
    }
}
