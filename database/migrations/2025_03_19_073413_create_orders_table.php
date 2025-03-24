<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->integer('customer_id');
            $table->string('order_date');
            $table->string('order_status');
            $table->string('total_products');
            $table->string('sub_total')->nullable();
            $table->string('vat')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('total', 8, 2)->default(0.00);
            $table->string('payment_status')->nullable();
            $table->decimal('due', 8, 2)->default(0.00);
            $table->decimal('pay', 8, 2)->default(0.00);

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
        Schema::dropIfExists('orders');
    }
};
