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
        Schema::create('project_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete();
            $table->date('work_date');
            $table->decimal('hours_worked', 5, 2); // e.g., 8.50 hours
            $table->text('description');
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->json('tasks_completed')->nullable(); // Array of completed tasks
            $table->json('challenges_faced')->nullable(); // Array of challenges
            $table->json('next_steps')->nullable(); // Array of next steps
            $table->enum('status', ['in_progress', 'completed', 'blocked', 'on_hold'])->default('in_progress');
            $table->timestamps();
            
            $table->index(['project_id', 'work_date']);
            $table->index(['developer_id', 'work_date']);
        });

        // Create project files table
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('progress_id')->nullable()->constrained('project_progress')->nullOnDelete();
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // e.g., 'image', 'document', 'code'
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can client see this file
            $table->timestamps();
            
            $table->index(['project_id', 'uploaded_by']);
        });

        // Create time tracking table
        Schema::create('time_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete();
            $table->date('tracking_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->text('activity_description');
            $table->enum('activity_type', ['development', 'testing', 'debugging', 'meeting', 'documentation', 'other']);
            $table->boolean('is_billable')->default(true);
            $table->timestamps();
            
            $table->index(['project_id', 'tracking_date']);
            $table->index(['developer_id', 'tracking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_tracking');
        Schema::dropIfExists('project_files');
        Schema::dropIfExists('project_progress');
    }
};