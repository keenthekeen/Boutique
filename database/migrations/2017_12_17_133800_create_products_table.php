<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
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
            $table->string('name');
            
            $table->string('picture', 200);
            $table->string('poster', 200)->nullable();
            
            $table->string('author');
            $table->string('facebook_url', 100)->nullable();
            $table->mediumText('description');
            $table->enum('type', ['หนังสือ', 'กระเป๋า', 'สมุด', 'ริสแบนด์', 'เสื้อ', 'แฟ้ม']);
            $table->decimal('price', 3,0);
            $table->integer('amount');
            
            $table->enum('book_type', ['เนื้อหา', 'โจทย์', 'เนื้อหาและโจทย์'])->nullable();
            $table->string('book_subject')->nullable();
            $table->integer('book_page')->nullable();
            $table->integer('book_question')->nullable();
            $table->string('book_example', 100)->nullable();
            
            $table->string('person')->nullable();
            $table->string('telephone')->nullable();
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
