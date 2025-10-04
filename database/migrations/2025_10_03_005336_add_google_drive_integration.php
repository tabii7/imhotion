<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Google Drive fields to project documents
        Schema::table('project_documents', function (Blueprint $table) {
            // Add missing columns only
            if (!Schema::hasColumn('project_documents', 'google_drive_file_id')) {
                $table->string('google_drive_file_id')->nullable();
            }
            if (!Schema::hasColumn('project_documents', 'google_drive_url')) {
                $table->string('google_drive_url')->nullable();
            }
            if (!Schema::hasColumn('project_documents', 'file_size')) {
                $table->bigInteger('file_size')->nullable();
            }
            if (!Schema::hasColumn('project_documents', 'google_drive_metadata')) {
                $table->json('google_drive_metadata')->nullable();
            }
            if (!Schema::hasColumn('project_documents', 'uploaded_to_drive_at')) {
                $table->timestamp('uploaded_to_drive_at')->nullable();
            }
        });

        // Create Google Drive settings table
        Schema::create('google_drive_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create file upload logs table
        Schema::create('file_upload_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_filename');
            $table->string('google_drive_file_id');
            $table->string('google_drive_url');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            $table->enum('status', ['uploading', 'completed', 'failed']);
            $table->text('error_message')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_upload_logs');
        Schema::dropIfExists('google_drive_settings');
        
        Schema::table('project_documents', function (Blueprint $table) {
            $table->dropColumn([
                'google_drive_file_id',
                'google_drive_url',
                'mime_type',
                'file_size',
                'google_drive_metadata',
                'uploaded_to_drive_at'
            ]);
        });
    }
};