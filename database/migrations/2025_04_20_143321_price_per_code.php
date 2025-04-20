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
        Schema::create('price_per_code', function (Blueprint $table) {
            $table->id();
            $table->date('price_date')->nullable();
            $table->integer('products_id')->nullable();
            $table->integer('unit_of_measurement_id')->nullable();
            $table->integer('price_code_id')->nullable();
            $table->decimal('unit_price', 18,2)->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('price_per_code');
    }
};
