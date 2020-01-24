<?php

namespace App\Http\Controllers;

use App\Fashion;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FashionController extends Controller
{
    public function getFashionView()
    {
        $fashions = Fashion::all();

        return view('admin.fashions')->with('fashions', $fashions);
    }

    public function editFashionView($id)
    {
        $fashion = Fashion::find($id);
        return view('admin.edit_fashions')->with(['fashion' => $fashion]);
    }

    public function createFashion(Request $request)
    {
        $validateFashionData = $request->validate([
            'fashion_title' => 'required|min:3|unique:fashions,fashion',
        ]);

        if (!File::isDirectory(storage_path('app/public/images/' .
            Str::slug($request->input('fashion_title'), '-')))) {
            File::makeDirectory(storage_path('app/public/images/' .
                Str::slug($request->input('fashion_title'), '-')));
        }
        Fashion::create([
            'fashion' => $request->input('fashion_title'),
            now()
        ]);

        return redirect(route('admin_fashion_view'))->with(['status' => 'Fashion successfully has been inserted']);
    }

    public function postEditFashion(Request $request, $id)
    {
        $validateFashionData = $request->validate([
            'fashion_title' => 'required|min:3',
        ]);
        $fashion = Fashion::find($id);
        $fashion_title = $request->input('fashion_title');

        if ($fashion->fashion !== $fashion_title) {
            File::moveDirectory(storage_path('app/public/images/' . Str::slug($fashion->fashion,'-')),
                storage_path('app/public/images/' . Str::slug($fashion_title,'-')));
        }


        Fashion::where('id', $id)->update(
            [
                'fashion' => $fashion_title,
            ]
        );
        \App\Product::where('fashion',$fashion->fashion)->update([
           'fashion' => $fashion_title
        ]);

        return redirect(route('admin_fashion_view'))->with(['status' => 'Fashion successfully has been updated']);
    }

    public function deleteFashion($id)
    {
        $fashion = Fashion::find($id);
        $products = \App\Product::where('fashion', $fashion->fashion)->get();
        foreach ($products as $product) {
            if ($product->fashion == $fashion->fashion) {
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
                \App\Product::where('id', $product->id)->delete();
            }
        }

//        if(File::exists(storage_path('app/public/images/'.$fashion->fashion.'/'.$fashion->image))) {
//            File::delete(storage_path('app/public/images/'.$fashion->fashion.'/'.$fashion->image));
//        }
        Fashion::where('id',$id)->delete();
        return redirect(route('admin_fashion_view'))->with(['status' => 'Fashion successfully has been deleted']);
    }
}
