<?php

namespace App\Http\Controllers;

use App\Category;
use App\Fashion;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function getSubcategoryView()
    {
        $fashions = Fashion::all();
        $subcategories = Subcategory::all();
        $categories = Category::all();
        return view('admin.subcategories')->with(['subcategories' => $subcategories, 'categories' => $categories,
            'fashions' => $fashions]);
    }

    public function editSubcategoryView($id)
    {
        $subcategory = Subcategory::where('id', $id)->first();
        return view('admin.edit_subcategories')->with(['subcategory' => $subcategory]);
    }

    public function createSubcategory(Request $request)
    {
        $validateSubcategoryData = $request->validate([
            'subcategory_title' => "required|min:3",
            'subcategory_category' => "required"
        ]);
        $category = Category::where('category', $request->input('subcategory_category'))->first();
        if (!File::isDirectory(storage_path('app/public/images/' . Str::slug($category->fashion, '-') . '/' .
            Str::slug($request->input('subcategory_category'), '-') . '/' .
            Str::slug($request->input('subcategory_title'), '-')))) {
            File::makeDirectory(storage_path('app/public/images/' . Str::slug($category->fashion, '-') . '/' .
                Str::slug($request->input('subcategory_category'), '-') . '/' .
                Str::slug($request->input('subcategory_title'), '-')));
        }
        Subcategory::create([
            'subcategory' => $request->input('subcategory_title'),
            'category' => $request->input('subcategory_category'),
            now()
        ]);

        return redirect(route('admin_subcategory_view'))->with(['status' => 'Subcategory successfully has been inserted']);
    }

    public function postEditSubcategory(Request $request, $id)
    {
        $validateSubcategoryData = $request->validate([
            'subcategory_title' => "required|min:3",
            'subcategory_category' => "required"
        ]);
        $original_subcategory = $request->input('original_subcategory');
        $fashion = Category::where('category',$request->input('subcategory_category'))->first();
        $title = $request->input('subcategory_title');
        if ($title != $original_subcategory) {
            File::moveDirectory(storage_path('app/public/images/' .
                Str::slug($fashion->fashion, '-') . '/' .
                Str::slug($request->input('subcategory_category'), '-').'/'.
                Str::slug($original_subcategory, '-')), storage_path('app/public/images/' .
                Str::slug($fashion->fashion, '-') . '/' .
                Str::slug($request->input('subcategory_category'), '-').'/'.
                Str::slug($title, '-')));

        }
        Subcategory::where('id',$id)->update(['subcategory'=>$title]);
        \App\Product::where('category',$request->input('subcategory_category'))
            ->where('subcategory',$original_subcategory)->update(['subcategory'=>$title]);

        return redirect(route('admin_subcategory_view'))->with(['status' => 'Subcategory successfully has been updated']);

    }
    public function deleteSubcategory($id) {
        $subcategory = Subcategory::find($id);
        $products = \App\Product::where('subcategory', $subcategory->subcategory)->get();
        foreach ($products as $product) {
            if ($product->subcategory == $subcategory->subcategory) {
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
        Subcategory::where('id',$id)->delete();
        return redirect(route('admin_subcategory_view'))->
        with(['status' => 'Subcategory and all products that are associated with it has successfully has been deleted']);
    }
}
