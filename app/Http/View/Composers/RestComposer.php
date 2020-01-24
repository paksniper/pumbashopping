<?php


namespace App\Http\View\Composers;


use App\Brand;
use App\Section;
use App\Subcategory;
use Illuminate\View\View;

class RestComposer
{
    public function compose(View $view) {
        $sections = Section::all();
        $brands = Brand::all();
        $view->with(['subcategories'=>$sections,'brands'=>$brands]);
    }
}
