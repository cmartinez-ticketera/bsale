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
        Schema::create('bsale_documents', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('document_number')->storedAs('JSON_UNQUOTE(data->"$.number")')->comment('SII invoice number.');
            $table->bigInteger('document_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale internal document id.');
            $table->string('internal_id')->storedAs('JSON_UNQUOTE(data->"$.salesId")')->comment('Internal order id.')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_documents');
    }
};
