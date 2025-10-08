<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('team_lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('specializations')->nullable(); // Array of specialization IDs
            $table->timestamps();
        });

        // Create team members pivot table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['member', 'lead', 'admin'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['team_id', 'user_id']);
        });

        // Create project teams pivot table
        Schema::create('project_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['project_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_teams');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
    }
};



