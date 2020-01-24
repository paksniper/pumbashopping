<?php

namespace App\Http\Controllers;

use App\Fashion;
use App\Filtercategory;
use App\Filtersubcategory;
use Illuminate\Http\Request;

class FilterSubcategoryController extends Controller
{
    public function getFilterSubcategoryView() {
        $subcategories = Filtersubcategory::all();
        $categories = Filtercategory::all();
        $fashions = Fashion::all();
        return view('admin.filter_subcategory')->with(['subcategory_filters'=> $subcategories,
            'categories' => $categories,'fashions' => $fashions]);
    }

    public function editFilterSubcategoryView($id) {
        $filter_subcategory = Filtersubcategory::find($id);
        $filter_categories = Filtercategory::all();
        $filter_fashions = Fashion::all();
        return view('admin.edit_filter_subcategory')->with(['subcategory_filter'=> $filter_subcategory,
            'categories' => $filter_categories,'fashions' => $filter_fashions]);
    }

    public function createFilterSubcategory(Request $request) {
        $validateFilterSubcategoryData = $request->validate([
            'filter_subcategory_title' => "required|min:3|unique:filtersubcategories,subcategory",
            'filter_subcategory_category' => "required",
        ]);

        Filtersubcategory::create([
            'subcategory'=> $request->input('filter_subcategory_title'),
            'category' => $request->input('filter_subcategory_category'),
            now()
        ]);
        return redirect(route('admin_filter_subcategory_view'))->with(['status' => 'Filter subcategory successfully has been inserted']);
    }

    public function postEditFilterSubcategory(Request $request , $id) {
        $validateFilterSubcategoryData = $request->validate([
            'filter_subcategory_title' => "required|min:3|unique:categories,category",
            'filter_subcategory_category' => "required"
        ]);

        $is_filter_subcategory_title_change = false;

        $original_data = Filtersubcategory::find($id);
        $title = $request->input('filter_subcategory_title');
        $category = $request->input('filter_subcategory_category');

        if($original_data->subcategory != $title) {
            Filtersubcategory::where('id',$id)->update([
               'subcategory' => $title
            ]);
            \App\Product::where($original_data->category,$original_data->category)->update([
               $original_data->category => $title
            ]);
            $is_filter_subcategory_title_change = true;
        }
        if($is_filter_subcategory_title_change) {
            return redirect(route('admin_filter_subcategory_view'))->with(['status' => 'Filter subcategory successfully has been updated']);
        } else {
            return redirect(route('admin_filter_subcategory_view'))->with(['status' => 'No change has been made!']);
        }
    }

    public function deleteFilterSubcategory($id) {
        Filtersubcategory::where('id',$id)->delete();
        return redirect(route('admin_filter_subcategory_view'))->with(['status' => 'Filter subcategory has been successfully delete']);
    }
}
