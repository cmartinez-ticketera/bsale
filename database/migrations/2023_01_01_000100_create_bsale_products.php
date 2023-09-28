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
        Schema::create('bsale_products', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale internal product id.')->index();
            $table->string('name')->storedAs('JSON_UNQUOTE(data->"$.name")')->comment('Bsale internal product id.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_products');
    }
};
