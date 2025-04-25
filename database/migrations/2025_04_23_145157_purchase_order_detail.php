<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('purchase_order_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_header_id')->nullable();
            $table->unsignedBigInteger('price_code_id')->nullable();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('tag_weight', 15, 2)->nullable();
            $table->decimal('unit_cost', 18, 2)->nullable();
            $table->decimal('total_cost', 18, 2)->nullable();
            $table->decimal('discount_rate', 18,2)->nullable();
            $table->decimal('discount_amount', 18,2)->nullable();
            $table->decimal('net_amount', 18,2)->nullable();
            $table->text('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->foreign('purchase_order_header_id')->references('id')->on('purchase_order_header');
            $table->foreign('products_id')->references('id')->on('products');
            $table->foreign('uom_id')->references('id')->on('unit_of_measurement');
            $table->foreign('price_code_id')->references('id')->on('price_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasTable('purchase_order_detail')) {
            Schema::table('purchase_order_detail', function (Blueprint $table) {
                $table->dropForeign(['purchase_order_header_id']);
                $table->dropForeign(['products_id']);
                $table->dropForeign(['uom_id']);
                $table->dropForeign(['price_code_id']);

            });
            Schema::dropIfExists('purchase_order_detail');
        }
    }
};
