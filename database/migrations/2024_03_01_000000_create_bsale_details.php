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
        Schema::create('bsale_details', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale detail id.');
            $table->bigInteger('variant_id')->storedAs('JSON_UNQUOTE(data->"$.variant.id")');
            $table->bigInteger('document_id');
            $table->index('document_id');
            $table->unique('internal_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_product_types');
    }
};
