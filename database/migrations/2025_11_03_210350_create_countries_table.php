<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('iso_code_2', 2)->unique(); // ISO 3166-1 alpha-2 (US, GB, etc.)
            $table->string('iso_code_3', 3)->unique(); // ISO 3166-1 alpha-3 (USA, GBR, etc.)
            $table->string('numeric_code', 3)->nullable(); // ISO 3166-1 numeric
            $table->string('phone_code')->nullable(); // International dialing code (+1, +44, etc.)
            $table->string('capital')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null');
            $table->string('region')->nullable(); // Africa, Asia, Europe, etc.
            $table->string('subregion')->nullable(); // Eastern Africa, Southern Asia, etc.
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emoji')->nullable(); // Country flag emoji
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
