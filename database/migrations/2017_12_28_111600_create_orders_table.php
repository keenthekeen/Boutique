<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable();
            $table->enum('type', ['promptpay', 'cash', 'card']);
            $table->enum('status', ['unpaid', 'pending', 'paid', 'delivered']);
            $table->decimal('price', 6, 2);
            $table->json('payment_note');
            $table->string('promotion')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('orders');
    }
}
