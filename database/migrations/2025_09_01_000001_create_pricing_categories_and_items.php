<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_category_id')->constrained('pricing_categories')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('price_unit')->nullable();
            $table->integer('duration_years')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->boolean('has_gift_box')->default(false);
            $table->boolean('has_project_files')->default(false);
            $table->boolean('has_weekends_included')->default(false);
            $table->text('note')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_items');
        Schema::dropIfExists('pricing_categories');
    }
};
