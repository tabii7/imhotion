<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Update role enum to include all four roles
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'administrator', 'developer', 'client'])
                  ->default('client')
                  ->after('password');
            
            // Add developer-specific fields
            $table->string('specialization')->nullable()->after('role');
            $table->text('skills')->nullable()->after('specialization');
            $table->string('experience_level')->nullable()->after('skills'); // junior, mid, senior
            $table->boolean('is_available')->default(true)->after('experience_level');
            $table->json('working_hours')->nullable()->after('is_available'); // {"monday": {"start": "09:00", "end": "17:00"}}
            
            // Add administrator-specific fields
            $table->text('notes')->nullable()->after('working_hours');
            $table->json('permissions')->nullable()->after('notes'); // Custom permissions for administrators
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'specialization',
                'skills',
                'experience_level',
                'is_available',
                'working_hours',
                'notes',
                'permissions'
            ]);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin','client'])->default('client')->after('password');
        });
    }
};