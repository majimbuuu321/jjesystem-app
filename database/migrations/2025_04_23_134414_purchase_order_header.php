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
        Schema::create('purchase_order_header', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->date('received_date')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('payment_terms_id')->nullable();
            $table->boolean('is_posted')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('warehouse_id')->references('id')->on('warehouse');
            $table->foreign('payment_terms_id')->references('id')->on('payment_terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('purchase_order_header', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['payment_terms_id']);
        });
        Schema::dropIfExists('purchase_order_header');
    }
};
