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
            $table->string('author');
            $table->enum('type', ['หนังสือ', 'กระเป๋า', 'สมุด', 'ริสแบนด์', 'เสื้อ', 'แฟ้ม', 'พวงกุญแจ']);
            $table->decimal('price', 3,0);
            
            $table->json('detail'); // Description, URL, (Question, Page)
            $table->string('picture')->nullable();
            $table->string('poster')->nullable();
            $table->string('book_example')->nullable();
            
            $table->enum('book_type', ['เนื้อหา', 'โจทย์', 'เนื้อหาและโจทย์'])->nullable();
            $table->json('book_subject')->nullable();
            
            $table->string('user_id');
            $table->json('owner_detail_1'); // Name, Phone, LINE, Email
            $table->json('owner_detail_2'); // Name, Phone, LINE, Email
            $table->json('payment'); // Bank, Account Number, Prompt Pay
            
            $table->enum('status', ['PENDING', 'OK'])->default('PENDING');
            $table->mediumText('note')->nullable();
            
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
