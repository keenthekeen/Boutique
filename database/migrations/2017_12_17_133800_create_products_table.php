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
            
            $table->boolean('has_picture');
            $table->boolean('has_poster');
            
            $table->string('author');
            $table->string('facebook_url', 100);
            $table->mediumText('description');
            $table->enum('type', ['หนังสือ', 'กระเป๋า', 'สมุด', 'ริสแบนด์', 'เสื้อ', 'แฟ้ม']);
            $table->decimal('price', 5,2);
            $table->integer('amount');
            
            $table->enum('book_type', ['เนื้อหา', 'โจทย์', 'เนื้อหาและโจทย์']);
            $table->string('book_subject');
            $table->integer('book_page');
            $table->integer('book_question');
            
            $table->string('person');
            $table->string('telephone');
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
