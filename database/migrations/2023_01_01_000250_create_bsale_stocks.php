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
        Schema::create('bsale_stocks', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale price list detail id.');
            $table->integer('quantity')->storedAs('JSON_UNQUOTE(data->"$.quantity")');
            $table->integer('quantity_reserved')->storedAs('JSON_UNQUOTE(data->"$.quantityReserved")');
            $table->integer('quantity_available')->storedAs('JSON_UNQUOTE(data->"$.quantityAvailable")');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_stocks');
    }
};
