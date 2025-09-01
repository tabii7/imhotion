<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add columns at the end; do NOT use ->after() so we don't depend on any prior column.
            if (!Schema::hasColumn('projects', 'pending_note')) {
                $table->string('pending_note')->nullable();
            }
            if (!Schema::hasColumn('projects', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (!Schema::hasColumn('projects', 'completed_at')) {
                $table->date('completed_at')->nullable();
            }
            if (!Schema::hasColumn('projects', 'cancel_reason')) {
                $table->string('cancel_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
            if (Schema::hasColumn('projects', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('projects', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('projects', 'pending_note')) {
                $table->dropColumn('pending_note');
            }
        });
    }
};
