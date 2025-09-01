<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->string('title')->nullable();
                $table->text('note')->nullable();
                $table->string('file_path')->nullable(); // stored on public disk
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
