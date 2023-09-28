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
        Schema::create('bsale_variants', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale internal variant id.');
            $table->bigInteger('product_id')->storedAs('JSON_UNQUOTE(data->"$.product.id")')->comment('Bsale internal product id.');
            $table->string('description')->storedAs('JSON_UNQUOTE(data->"$.description")')->comment('Bsale description.');
            $table->index(['internal_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_variants');
    }
};
