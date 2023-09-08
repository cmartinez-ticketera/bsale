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
        Schema::create('bsale_prices', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('price_list_id')->comment('Bsale price list id.');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale price list detail id.');
            $table->decimal('price', 10)->storedAs('JSON_UNQUOTE(data->"$.variantValue")')->comment('Price without taxes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_prices');
    }
};
