<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to projects
        Schema::table('projects', function (Blueprint $table) {
            // Add notes column if it doesn't exist
            if (!Schema::hasColumn('projects', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            // Add other missing columns
            if (!Schema::hasColumn('projects', 'client_requirements')) {
                $table->text('client_requirements')->nullable();
            }
            if (!Schema::hasColumn('projects', 'administrator_notes')) {
                $table->text('administrator_notes')->nullable();
            }
            if (!Schema::hasColumn('projects', 'developer_notes')) {
                $table->text('developer_notes')->nullable();
            }
            if (!Schema::hasColumn('projects', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            }
            if (!Schema::hasColumn('projects', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }
            if (!Schema::hasColumn('projects', 'started_at')) {
                $table->timestamp('started_at')->nullable();
            }
            if (!Schema::hasColumn('projects', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable();
            }
        });

        // Create project requirements table
        Schema::create('project_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Client who submitted
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['feature', 'bug_fix', 'enhancement', 'integration', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical']);
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'completed']);
            $table->text('administrator_feedback')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // Create project activities table for tracking
        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Who performed the activity
            $table->string('type'); // status_change, file_upload, comment, time_log, etc.
            $table->text('description');
            $table->json('metadata')->nullable(); // Additional data like old_status, new_status, etc.
            $table->timestamp('performed_at');
            $table->timestamps();
        });

        // Create project time logs table
        Schema::create('project_time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->integer('hours_spent');
            $table->date('work_date');
            $table->timestamp('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_time_logs');
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('project_requirements');
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_developer_id',
                'assigned_administrator_id',
                'client_requirements',
                'administrator_notes',
                'developer_notes',
                'priority',
                'assigned_at',
                'started_at',
                'last_activity_at'
            ]);
        });
    }
};