<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->string('name');
                $t->string('topic')->nullable();
                $t->enum('status', ['in_progress','completed','on_hold','finalized','canceled'])->default('in_progress');
                $t->date('delivery_date')->nullable();
                $t->unsignedTinyInteger('progress')->default(0); // 0..100
                $t->timestamps();
            });
            return;
        }

        // Table exists: add only missing columns (idempotent)
        Schema::table('projects', function (Blueprint $t) {
            if (!Schema::hasColumn('projects', 'user_id')) {
                $t->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('projects', 'name')) {
                $t->string('name')->after('user_id');
            }
            if (!Schema::hasColumn('projects', 'topic')) {
                $t->string('topic')->nullable()->after('name');
            }
            if (!Schema::hasColumn('projects', 'status')) {
                $t->enum('status', ['in_progress','completed','on_hold','finalized','canceled'])
                  ->default('in_progress')
                  ->after('topic');
            }
            if (!Schema::hasColumn('projects', 'delivery_date')) {
                $t->date('delivery_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('projects', 'progress')) {
                $t->unsignedTinyInteger('progress')->default(0)->after('delivery_date');
            }
            if (!Schema::hasColumn('projects', 'created_at')) {
                $t->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
