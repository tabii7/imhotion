<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('service_type', ['ui_ux_design', 'web_development', 'backend_development', 'mobile_development', 'database_development', 'full_stack', 'consulting']);
            $table->enum('time_unit', ['hour', 'day', 'week', 'month']);
            $table->decimal('price_per_unit', 10, 2);
            $table->integer('min_units')->default(1);
            $table->integer('max_units')->nullable();
            $table->json('features')->nullable(); // Array of features included
            $table->json('specializations')->nullable(); // Required specializations
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};