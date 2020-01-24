<?php

namespace App\Http\Controllers;

use App\Category;
use App\Fashion;
use App\Filtercategory;
use App\Filtersubcategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class FilterController extends Controller
{
    public function getFilterView()
    {
        $filters = Filtercategory::all();
        $fashions = Fashion::all();
        return view('admin.filters')->with(['filters' => $filters, 'fashions' => $fashions]);
    }

    public function editFilterCategoryView($id)
    {
        $fashions = Fashion::all();
        $category = Filtercategory::where('id', $id)->first();
        return view('admin.edit_filters')->with(['filtercategory' => $category, 'fashions' => $fashions]);
    }

    public function createFilter(Request $request)
    {
        $validateCategoryData = $request->validate([
            'filter_title' => "required|min:3|unique:categories,category",
        ]);
        $filter_category = $request->input('filter_title');
        Filtercategory::create([
            'category' => $filter_category,
            now()
        ]);

        if (!Schema::hasColumn('products', Str::slug($filter_category,'_'))) {
            Schema::table('products', function (Blueprint $table) use ($filter_category) {
                $table->string(Str::slug($filter_category, '_'))->nullable();
            });
        }
        return redirect(route('admin_filter_view'))->with(['status' => 'Filter Category successfully has been inserted']);
    }

    public function postEditFilterCategory(Request $request, $id)
    {
        $validateCategoryData = $request->validate([
            'filter_title' => "required|min:3|unique:categories,category"
        ]);

        $is_filter_category_change = false;

        $original_title = Filtercategory::find($id);
        $filter_category = $request->input('filter_title');
//        dd($original_title->category);

        if ($original_title->category != $filter_category) {

            if (Schema::hasColumn('products', Str::slug($original_title->category, '_'))) {
                Schema::table('products', function (Blueprint $table) use ($filter_category, $original_title) {
//                    dd('  '.$original_title->category,$filter_category);
                    $table->renameColumn(Str::slug($original_title->category, '_'), Str::slug($filter_category, '_'));
                });
            }
            Filtercategory::where('id', $id)->update([
                'category' => $filter_category,
            ]);
            Filtersubcategory::where('category', $original_title->category)->update([
                'category' => $request->input('filter_title')
            ]);
            $is_filter_category_change = true;
        }

        if ($is_filter_category_change === true) {
            return redirect(route('admin_filter_view'))->with(['status' => 'Filter Category successfully has been updated']);
        } else {
            return redirect(route('admin_filter_view'))->with(['status' => 'No change has been made!']);
        }
    }

    public function deleteFilterCategory($id)
    {

        $is_column_empty = false;
        $original_data = Filtercategory::find($id);
        $category = str_replace(' ', '_', $original_data->category);
        Filtercategory::where('id', $id)->delete();
        Filtersubcategory::where('category', $original_data->category)->delete();
        if (Schema::hasColumn('products', Str::slug($original_data->category, '_'))) {
            $product_columns = \App\Product::whereNotNull($category)->get();
            if ($product_columns->isEmpty()) {
                $is_column_empty = true;
            }
            if ($is_column_empty === true) {
                Schema::table('products', function (Blueprint $table) use ($original_data) {
                    $table->dropColumn(Str::slug($original_data->category, '_'));
                });
            }
        }
        return redirect(route('admin_filter_view'))->with(['status' => 'Filter Category successfully has been deleted']);
    }
}
