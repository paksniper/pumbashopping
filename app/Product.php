<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'title','section','category','subcategory','fashion','price',
        'discount','percentage','brand','trader','image',
        'specification','feature','description'
        ];
}
