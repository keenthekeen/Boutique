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
            $table->string('author');
            $table->enum('type', ['หนังสือ', 'กระเป๋า', 'สมุด', 'ริสแบนด์', 'เสื้อ', 'แฟ้ม']);
            $table->decimal('price', 3,0);
            
            $table->json('detail'); // Description, Poster, URL
            
            $table->enum('book_type', ['เนื้อหา', 'โจทย์', 'เนื้อหาและโจทย์'])->nullable();
            $table->string('book_subject')->nullable();
            $table->json('book_detail');
            
            $table->string('user_id');
            $table->json('owner_detail_1'); // Name, Phone, LINE, Email
            $table->json('owner_detail_2'); // Name, Phone, LINE, Email
            $table->json('payment'); // Bank, Account Number, Prompt Pay
            
            $table->softDeletes();
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
