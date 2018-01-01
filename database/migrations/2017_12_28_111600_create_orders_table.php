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
            $table->enum('type', ['pos', 'cash', 'card']);
            $table->enum('status', ['unpaid', 'paid', 'delivered']);
            $table->decimal('price', 6, 2);
            $table->string('payment_note')->nullable();
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
