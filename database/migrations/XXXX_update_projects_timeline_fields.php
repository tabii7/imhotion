<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $t) {
            if (!Schema::hasColumn('projects', 'start_date')) {
                $t->date('start_date')->nullable()->after('topic');
            }
            if (!Schema::hasColumn('projects', 'estimated_days')) {
                $t->unsignedSmallInteger('estimated_days')->default(0)->after('start_date');
            }
            if (!Schema::hasColumn('projects', 'extra_days')) {
                $t->unsignedSmallInteger('extra_days')->default(0)->after('estimated_days');
            }
            if (!Schema::hasColumn('projects', 'used_days')) {
                $t->unsignedSmallInteger('used_days')->default(0)->after('extra_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $t) {
            foreach (['start_date','estimated_days','extra_days','used_days'] as $col) {
                if (Schema::hasColumn('projects', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
