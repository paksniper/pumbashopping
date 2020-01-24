<?php

namespace App\Http\Controllers;

use App\Section;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function getSectionView()
    {
        $sections = Section::all();

        return view('admin.sections')->with(
            [
                'sections' => $sections
            ]
        );

    }

    public function editSectionView($id)
    {
        $section = Section::where('id', $id)->first();
        return view('admin.edit_sections')->with('section', $section);
    }

    public function createSection(Request $request)
    {

        $validateSectionData = $request->validate([
            'section_title' => "required|min:3|unique:sections,title",
        ]);

        Section::create([
            'title' => $request->input('section_title'), now()
        ]);
        return redirect(route('admin_section_view'))->with(['status' => 'Section successfully has been inserted']);
    }

    public function postEditSection(Request $request, $id)
    {
        $original_title = $request->input('original_title');
        $title = $request->input('section_title');
        $validateSectionData = $request->validate([
            'section_title' => 'nullable|min:3'
        ]);
        if($original_title != $title) {
            $products = \App\Product::where('section',$original_title)->get();
            Slider::where('section',$original_title)->update(['section' => $title]);
            foreach ($products as $product) {
                if($product->section == $original_title) {
                    $myproduct = \App\Product::find($product->id);
                    $myproduct->section = $title;
                    $myproduct->save();
                }
            }
        }

        Section::where('id', $id)->update([
            'title' => $title
        ]);
        return redirect(route('admin_section_view'))->with(['status' => 'Section successfully has been updated']);
    }

    public function deleteSection($id) {
        $section  = Section::where('id',$id)->first();
        $products = \App\Product::where('section',$section->title)->get();
        foreach ($products as $product) {
            if($product->section == $section->title) {
                File::delete(storage_path('app/public/images/'.
                    Str::slug($product->fashion,'-').'/'
                    .Str::slug($product->category,'-').'/'.
                    Str::slug($product->subcategory,'-').'/'.$product->image));

                File::delete(storage_path('app/public/images/'.
                    Str::slug($product->fashion,'-').'/'
                    .Str::slug($product->category,'-').'/'.
                    Str::slug($product->subcategory,'-').'/resize_'.$product->image));

                \App\Product::where('id',$product->id)->delete();
            }
        }
        Section::where('id',$id)->delete();
        return redirect(route('admin_section_view'))->with(['status' => 'Section and all of its related products have successfully been deleted']);
    }
}
