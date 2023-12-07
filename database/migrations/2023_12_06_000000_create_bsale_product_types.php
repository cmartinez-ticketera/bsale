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
        Schema::create('bsale_product_types', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('name')->storedAs('JSON_UNQUOTE(data->"$.name")');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale product type id.');
            $table->index(['internal_id']);
            $table->timestamps();
        });

        Schema::table('bsale_products', function (Blueprint $table) {
            $table->after('internal_id', function (Blueprint $table) {
                $table->bigInteger('product_type_id')->storedAs('JSON_UNQUOTE(data->"$.product_type.id")');
                $table->boolean('state')->storedAs('JSON_UNQUOTE(data->"$.state")');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_product_types');

        Schema::table('bsale_products', function (Blueprint $table) {
            $table->dropColumn('product_type_id');
            $table->dropColumn('state');
        });
    }
};
