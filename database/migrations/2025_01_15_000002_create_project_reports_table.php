<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['project_status', 'team_performance', 'time_tracking', 'budget_analysis', 'custom'])->default('custom');
            $table->json('filters')->nullable(); // Store report filters as JSON
            $table->json('data')->nullable(); // Store report data as JSON
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        // Create report subscriptions table for scheduled reports
        Schema::create('report_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('project_reports')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->time('send_time')->default('09:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            
            $table->unique(['report_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_subscriptions');
        Schema::dropIfExists('project_reports');
    }
};



