<?php

namespace App\Http\Controllers;

use App\Category;
use App\Fashion;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getCategoryView() {
        $categories = Category::all();
        $fashions = Fashion::all();
        return view('admin.categories')->with(['categories'=> $categories,'fashions' => $fashions]);
    }

    public function editCategoryView($id) {
        $category = Category::where('id',$id)->first();
        $fashions = Fashion::all();
        return view('admin.edit_categories')->with(['fashions' => $fashions,'category' => $category]);
    }

    public function createCategory(Request $request) {
        $validateCategoryData = $request->validate([
            'category_title' => "required|min:3|unique:categories,category",
            'category_fashion' => "required"
        ]);

        if (!File::isDirectory(storage_path('app/public/images/'.Str::slug($request->input('category_fashion'),'-').'/'.Str::slug($request->input('category_title'),'-')))) {
            File::makeDirectory(storage_path('app/public/images/'.Str::slug($request->input('category_fashion'),'-').'/'.Str::slug($request->input('category_title'),'-')));
        }

        Category::create([
            'category'=> $request->input('category_title'),
            'fashion' => $request->input('category_fashion'),
            now()
        ]);
        return redirect(route('admin_category_view'))->with(['status' => 'Category successfully has been inserted']);
    }

    public function postEditCategory(Request $request,$id) {
//        $validateCategoryData = $request->validate([
//            'category_title' => "required|min:3|unique:categories,category",
//            'category_fashion' => "required"
//        ]);
        $is_fashion_change = false;
        $is_category_change = false;
        $original_data = Category::find($id);
        $original_category = $original_data->category;
        $original_fashion = $original_data->fashion;
        $title = $request->input('category_title');
        $fashion = $request->input('category_fashion');

        if($original_category != $title || empty($title)) {
            $validateCategoryData = $request->validate([
                'category_title' => "required|min:3|unique:categories,category",
            ]);
        }

        $fashion_db = Category::where('fashion',$fashion)->get();

        if($fashion != $original_fashion) {
            File::moveDirectory(storage_path('app/public/images/'.
                Str::slug($original_fashion,'-').'/'.
                Str::slug($original_category)), storage_path('app/public/images/'.
                Str::slug($fashion,'-').'/'.
                Str::slug($original_category,'-')));

            Category::where('id',$id)->update(['fashion'=>$fashion]);
            \App\Product::where('category',$original_category)->update(['fashion'=>$fashion]);
            $is_fashion_change = true;
        }

        if($title != $original_category) {
            foreach($fashion_db as $fashion) {
                if($fashion->category != $title){
                    File::moveDirectory(storage_path('app/public/images/'.
                        Str::slug($request->input('category_fashion'),'-').'/'.
                        Str::slug($original_category,'-')), storage_path('app/public/images/'.
                            Str::slug($request->input('category_fashion'),'-').'/'
                            .Str::slug($title,'-')));
                }
            }
            Category::where('id',$id)->update(['category'=>$title]);
            Subcategory::where('category',$original_category)->update(['category'=>$title]);
            \App\Product::where('category',$original_category)->update(['category'=>$title]);
            $is_category_change = true;
        }

        if($is_fashion_change === true || $is_category_change === true) {
            return redirect(route('admin_category_view'))->with(['status' => 'Category successfully has been updated']);
        } else {
            return redirect(route('admin_category_view'))->with(['status' => 'No change has been made!']);
        }

    }

    public function deleteCategory($id) {
        $category = Category::find($id);
        $products = \App\Product::where('category', $category->category)->get();
        foreach ($products as $product) {
            if ($product->category == $category->category) {
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
        Category::where('id',$id)->delete();
        Subcategory::where('category',$category->category)->delete();
        return redirect(route('admin_category_view'))->with(['status' => 'Category successfully has been updated']);
    }
}
