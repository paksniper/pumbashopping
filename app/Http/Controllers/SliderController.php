<?php

namespace App\Http\Controllers;

use App\Section;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function getSliderView() {
        $sliders = Slider::all();
        $sections = Section::all();
        return view('admin.sliders')->with(
            [
                'sliders' => $sliders,
                'sections' => $sections
            ]
        );
    }

    public function editSliderView($id) {
        $slider = Slider::where('id',$id)->first();
        $sections = Section::all();
        return view('admin.edit_sliders')->with(['slider'=> $slider,'sections'=>$sections]);
    }

    public function createSlider(Request $request) {

        $validateSliderData = $request->validate([
            'slider_section' => "required",
            'slider_image' => 'required|image|dimensions:min_width=1920,min_height=400,max_width=1920,max_height=400|mimes:jpeg'
        ]);

        $image = $request->file('slider_image');
        $image->store('public/images/sliders/');

//        dd($request->all());

        if($image->isValid()) {

            Slider::create([
                'section' => $request->input('slider_section'),
                'slider_image' => $image->hashName(),now()
            ]);

            return redirect(route('admin_slider_view'))->with(['status' => 'Slider successfully has been inserted']);

        }

    }

    public function postEditSlider(Request $request,$id) {
        $validateSliderData = $request->validate([
            'slider_section' => "required",
            'slider_image' => 'nullable|image|dimensions:min_width=1920,min_height=400,max_width=1920,max_height=400|mimes:jpeg,png'
        ]);

        $slider = Slider::where('id',$id)->first();
        $image = null;
        $new_image = $slider->slider_image;
        if($request->hasFile('slider_image')) {
            $image = $request->file('slider_image');
            File::delete(storage_path('app/public/images/sliders/'.$slider->slider_image));
            $image->store('public/images/sliders');
            if($image->isValid()) {
                $new_image = $image->hashName();
            }
        }
        Slider::where('id',$id)->update([
            'section' => $request->input('slider_section'),
            'slider_image' => $new_image
        ]);

        return redirect(route('admin_slider_view'))->with(['status' => 'Slider successfully has been updated']);

    }

    public function deleteSlider($id) {
        $slider = Slider::where('id',$id)->first();
        if(file_exists(storage_path('app/public/images/sliders/'.$slider->slider_image))) {
            File::delete(storage_path('app/public/images/sliders/'.$slider->slider_image));
        }
        Slider::where('id',$id)->delete();
        return redirect(route('admin_slider_view'))->with(['status' => 'Slider successfully has been deleted']);

    }
}
