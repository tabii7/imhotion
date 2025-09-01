<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns only if they don't already exist
        Schema::table('project_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('project_documents', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('project_documents', 'filename')) {
                // place it after name if name exists, otherwise after id
                $after = Schema::hasColumn('project_documents', 'name') ? 'name' : 'id';
                $table->string('filename')->nullable()->after($after);
            }
        });

        // Backfill only if the column exists
        if (Schema::hasColumn('project_documents', 'filename')) {
            DB::statement("
                UPDATE project_documents
                SET filename = COALESCE(filename, SUBSTRING_INDEX(path, '/', -1))
                WHERE filename IS NULL OR filename = ''
            ");
        }

        if (Schema::hasColumn('project_documents', 'name')) {
            DB::statement("
                UPDATE project_documents
                SET name = COALESCE(name, SUBSTRING_INDEX(SUBSTRING_INDEX(path, '/', -1), '.', 1))
                WHERE name IS NULL OR name = ''
            ");
        }
    }

    public function down(): void
    {
        // Drop only columns that exist
        Schema::table('project_documents', function (Blueprint $table) {
            if (Schema::hasColumn('project_documents', 'filename')) {
                $table->dropColumn('filename');
            }
            if (Schema::hasColumn('project_documents', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
