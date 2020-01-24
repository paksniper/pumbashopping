<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('section');
            $table->string('category');
            $table->string('subcategory');
            $table->string('fashion');
            $table->float('price');
            $table->float('discount')->nullable();
            $table->float('percentage')->nullable();
            $table->string('brand');
            $table->string('trader');
            $table->string('image');
            $table->text('specification');
            $table->text('feature');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
