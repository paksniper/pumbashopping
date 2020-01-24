<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_section' => 'required',
            'product_fashion' => 'required',
            'product_brand' => 'required',
            'product_category' => 'required',
            'product_subcategory' => 'required',
            'product_trader' => 'required',
            'product_title' => 'required|min:5',
            'product_discount_price' => 'nullable|numeric',
            'product_price' => 'required|numeric',
            'product_specification' => 'required|min:10',
            'product_feature' => 'required|min:10',
            'product_description' => 'required|min:10',

        ];
    }

    public function messages()
    {
        return [
            'product_image.dimensions' => 'The image should be 850 in width and height',
            'product_section.required' => 'Select a section for the product',
            'product_fashion.required' => 'Choose fashion for the product',
            'product_brand.required' => 'Product brand is required',
            'product_trader.required' => 'Select trader for the product',
            'product_category.required' => 'Please choose category for the product',
            'product_subcategory.required' => 'Please choose subcategory for the product'
        ];
    }
}
