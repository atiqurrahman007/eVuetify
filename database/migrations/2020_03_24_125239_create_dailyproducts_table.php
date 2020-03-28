<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailyproducts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('cat_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable()->default('1');
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_short_desc')->nullable();
            $table->string('buying_price')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('buying_date')->nullable();
            $table->string('date_expired')->nullable();
            $table->string('product_image')->default('default.png');
            $table->string('product_quantity')->nullable();
            $table->string('product_unit')->nullable();
            $table->string('color')->nullable();
            $table->boolean('product_status')->default(false);
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
        Schema::dropIfExists('dailyproducts');
    }
}
