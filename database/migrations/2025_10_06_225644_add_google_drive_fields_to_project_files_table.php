<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->string('google_drive_file_id')->nullable()->after('is_public');
            $table->string('google_drive_url')->nullable()->after('google_drive_file_id');
        });
    }

    public function down(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->dropColumn(['google_drive_file_id', 'google_drive_url']);
        });
    }
};