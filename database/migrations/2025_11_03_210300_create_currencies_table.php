<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // ISO 4217 (USD, EUR, etc.)
            $table->string('name');
            $table->string('symbol', 10)->nullable(); // $, €, £, etc.
            $table->string('decimal_mark', 1)->default('.'); // . or ,
            $table->string('thousands_separator', 1)->default(','); // , or .
            $table->integer('decimal_places')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
