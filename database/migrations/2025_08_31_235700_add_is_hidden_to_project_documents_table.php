<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('project_documents', 'is_hidden')) {
            Schema::table('project_documents', function (Blueprint $table) {
                $table->boolean('is_hidden')->default(false)->after('size');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('project_documents', 'is_hidden')) {
            Schema::table('project_documents', function (Blueprint $table) {
                $table->dropColumn('is_hidden');
            });
        }
    }
};
