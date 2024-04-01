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
        Schema::create('bsale_returns', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->bigInteger('internal_id')->storedAs('JSON_UNQUOTE(data->"$.id")')->comment('Bsale return id.');
            $table->bigInteger('reference_document_id')->storedAs('JSON_UNQUOTE(data->"$.reference_document.id")');
            $table->bigInteger('credit_note_id')->storedAs('JSON_UNQUOTE(data->"$.credit_note.id")');
            $table->unique('internal_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bsale_returns');
    }
};
