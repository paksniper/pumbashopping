<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Fashion;
use App\Filtercategory;
use App\Filtersubcategory;
use App\Http\Requests\CreateProduct;
use App\Section;
use App\Subcategory;
use App\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Product extends Controller
{
    public function getProductView()
    {
        $sections = Section::all();
        $fashions = Fashion::all();
        $categories = Category::all();
        $filter_categories = Filtercategory::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        $traders = Trader::all();

        return view('admin.products')->with(
            [
                'sections' => $sections,
                'fashions' => $fashions,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'brands' => $brands,
                'traders' => $traders,
                'filter_categories' => $filter_categories
            ]
        );

    }

    public function editProductView($id)
    {

        $product = \App\Product::where('id', $id)->first();
        $sections = Section::all();
        $fashions = Fashion::all();
        $categories = Category::where('fashion', $product->fashion)->get();
        $subcategories = Subcategory::where('category', $product->category)->get();
        $product_table_columns = Schema::getColumnListing('products');
        $filter_categories = Filtercategory::all();
        $filter_html = '';
        foreach ($product_table_columns as $column) {
            $filter_category = Filtercategory::where('category', str_replace('_', ' ', $column))->first();
            if (!empty($filter_category->category)) {
                $product_filter = \App\Product::find($id);
                if (!empty($product_filter->$column)) {
                    $filter_subcategories = Filtersubcategory::where('category', str_replace('_', ' ', $column))->get();

                    $filter_html .= "<div id='".$column."' class='col-md-2'><div class='form-group w-100'>
                    <label class='form-label'>Select " . $filter_category->category . " </label>
                    <select name='" . Str::slug($filter_category->category, '_') . "' class='form-control'>
                    <option value='" . $product_filter->$column . "'>" . $product_filter->$column . "</option>";

                    foreach ($filter_subcategories as $subcategory) {
                        if ($product_filter->$column !== $subcategory->subcategory) {
                            $filter_html .= "<option value='" . $subcategory->subcategory . "'>" . $subcategory->subcategory . "</option>";
                        }
                    }
                    $filter_html .= "</select></div></div>";
                }
            }
        }
        $brands = Brand::all();
        $traders = Trader::all();
        return view('admin.edit_products')->with(
            [
                'product' => $product,
                'sections' => $sections,
                'fashions' => $fashions,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'brands' => $brands,
                'traders' => $traders,
                'filter_categories' => $filter_categories,
                'filter_html' => $filter_html
            ]
        );
    }

    public function createProduct(CreateProduct $request)
    {

        $validated = $request->validated();
        $request->validate([
            'product_image' => 'required|image|dimensions:min_width=850,min_height=850,max_width=850,max_height=850|mimes:jpeg'
        ]);

        $mycolumn = new Collection();
        $product_table_columns = Schema::getColumnListing('products');
        foreach ($product_table_columns as $column) {
            if ($request->has($column)) {
                $mycolumn->push($column);
            }
        }
//        dd($mycolumn);
        $percentage = null;
        $image = $request->file('product_image');
        $image->store('public/images/' .
            Str::slug($validated['product_fashion'], '-') . '/'
            . Str::slug($validated['product_category'], '-') . '/' .
            Str::slug($validated['product_subcategory'], '-') . '/');

        $image_resize = Image::make($request->file('product_image'));
        $image_resize->resize('395', '395')
            ->save(storage_path('app/public/images/' .
                Str::slug($validated['product_fashion'], '-') . '/'
                . Str::slug($validated['product_category'], '-') . '/' .
                Str::slug($validated['product_subcategory'], '-') . '/resize_' . $image->hashName()));
        if ($validated['product_discount_price'] != "") {
            $percentage = $validated['product_price'] - $validated['product_discount_price'];
            $percentage = round(($percentage / $validated['product_price']) * 100);
        }

        if ($image->isValid()) {
            $product_inserted = \App\Product::create([
                'title' => $validated['product_title'],
                'section' => $validated['product_section'],
                'category' => $validated['product_category'],
                'subcategory' => $validated['product_subcategory'],
                'fashion' => $validated['product_fashion'],
                'price' => $validated['product_price'],
                'discount' => $validated['product_discount_price'],
                'percentage' => $percentage,
                'brand' => $validated['product_brand'],
                'trader' => $validated['product_trader'],
                'image' => $image->hashName(),
                'specification' => $validated['product_specification'],
                'feature' => $validated['product_feature'],
                'description' => $validated['product_description'],
                now()
            ]);
            foreach ($mycolumn as $column) {
                \App\Product::where('id', $product_inserted->id)->update([
                    $column => $request->input($column)
                ]);
            }

        }
        return redirect(route('admin_product_view'))->with(['status' => 'Product successfully has been inserted']);
    }

    public function postEditProduct(CreateProduct $request, $id)
    {

        $validateEditProductData = $request->validated();
        $request->validate([
            'product_image' => 'nullable|image|dimensions:min_width=850,min_height=850,max_width=850,max_height=850|mimes:jpeg'
        ]);

        $original_data = \App\Product::find($id);
        $original_fashion = $original_data->fashion;
        $original_category = $original_data->category;
        $original_subcategory = $original_data->subcategory;

        $mycolumn = new Collection();
        $product_table_columns = Schema::getColumnListing('products');
        foreach ($product_table_columns as $column) {
            if ($request->has($column)) {
                $mycolumn->push($column);
            }
        }

        $product = \App\Product::where('id', $id)->first();
        $percentage = null;
        $image = null;
        $image_name = $product->image;

        if ($request->hasFile('product_image')) {

            File::delete(storage_path('app/public/images/' .
                Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                Str::slug($validateEditProductData['product_subcategory'], '-') . '/resize_' . $product->image));

            File::delete(storage_path('app/public/images/' .
                Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                Str::slug($validateEditProductData['product_subcategory'], '-') . '/' . $product->image));

            $image = $request->file('product_image');
            $image->store('public/images/' .
                Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                Str::slug($validateEditProductData['product_subcategory'], '-') . '/');

            $image_resize = Image::make($request->file('product_image'));
            $image_resize->resize('395', '395')
                ->save(storage_path('app/public/images/' .
                    Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                    . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                    Str::slug($validateEditProductData['product_subcategory'], '-') . '/resize_' . $image->hashName()));
            $image_name = $image->hashName();
        }
        if ($original_fashion != $request->input('product_fashion') ||
            $original_category != $request->input('product_category') ||
            $original_subcategory != $request->input('product_subcategory')) {
            File::move(storage_path('app/public/images/' .
                Str::slug($original_fashion, '-') . '/'
                . Str::slug($original_category, '-') . '/' .
                Str::slug($original_subcategory, '-') . '/' . $product->image),
                storage_path('app/public/images/' .
                    Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                    . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                    Str::slug($validateEditProductData['product_subcategory'], '-') . '/' . $product->image));

            File::move(storage_path('app/public/images/' .
                Str::slug($original_fashion, '-') . '/'
                . Str::slug($original_category, '-') . '/' .
                Str::slug($original_subcategory, '-') . '/resize_' . $product->image),
                storage_path('app/public/images/' .
                    Str::slug($validateEditProductData['product_fashion'], '-') . '/'
                    . Str::slug($validateEditProductData['product_category'], '-') . '/' .
                    Str::slug($validateEditProductData['product_subcategory'], '-') . '/resize_' . $product->image));
        }

        if ($validateEditProductData['product_discount_price'] != "") {
            $percentage = $validateEditProductData['product_price'] - $validateEditProductData['product_discount_price'];
            $percentage = round(($percentage / $validateEditProductData['product_price']) * 100);
        }


        $product_inserted = \App\Product::where('id', $id)->update([
            'title' => $validateEditProductData['product_title'],
            'section' => $validateEditProductData['product_section'],
            'category' => $validateEditProductData['product_category'],
            'subcategory' => $validateEditProductData['product_subcategory'],
            'fashion' => $validateEditProductData['product_fashion'],
            'price' => $validateEditProductData['product_price'],
            'discount' => $validateEditProductData['product_discount_price'],
            'percentage' => $percentage,
            'brand' => $validateEditProductData['product_brand'],
            'trader' => $validateEditProductData['product_trader'],
            'image' => $image_name,
            'specification' => $validateEditProductData['product_specification'],
            'feature' => $validateEditProductData['product_feature'],
            'description' => $validateEditProductData['product_description']
        ]);
        foreach ($mycolumn as $column) {
            \App\Product::where('id', $id)->update([
                $column => $request->input($column)
            ]);
        }
        return redirect(route('admin_product_view'))->with(['status' => 'Product successfully has been updated']);
    }

    public function deleteProduct($id)
    {
        $product = \App\Product::where('id', $id)->first();

        if (file_exists(storage_path('app/public/images/' .
            Str::slug($product->fashion, '-') . '/'
            . Str::slug($product->category, '-') . '/' .
            Str::slug($product->subcategory, '-') . '/' . $product->image))) {

            File::delete(storage_path('app/public/images/' .
                Str::slug($product->fashion, '-') . '/'
                . Str::slug($product->category, '-') . '/' .
                Str::slug($product->subcategory, '-') . '/' . $product->image));

            File::delete(storage_path('app/public/images/' .
                Str::slug($product->fashion, '-') . '/'
                . Str::slug($product->category, '-') . '/' .
                Str::slug($product->subcategory, '-') . '/resize_' . $product->image));
        }

        \App\Product::where('id', $id)->delete();
        return redirect(route('admin_product_view'))->with(['status' => 'Product successfully has been deleted']);
    }

}
