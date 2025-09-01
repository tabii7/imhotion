<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->enum('status', ['in_progress', 'completed', 'on_hold', 'finalized', 'cancelled'])->default('in_progress');
                $table->date('delivery_date')->nullable();
                $table->unsignedInteger('days_used')->default(0);
                $table->decimal('day_budget', 10, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
