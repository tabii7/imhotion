<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 32)->default('client')->after('password');
            }
            if (!Schema::hasColumn('users', 'balance_days')) {
                $table->integer('balance_days')->default(0)->after('role');
            }

            // Optional extras your model mentions (safe if they already exist)
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('postal_code');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country', 60)->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 40)->nullable()->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'country')) $table->dropColumn('country');
            if (Schema::hasColumn('users', 'city')) $table->dropColumn('city');
            if (Schema::hasColumn('users', 'postal_code')) $table->dropColumn('postal_code');
            if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
            if (Schema::hasColumn('users', 'full_name')) $table->dropColumn('full_name');
            if (Schema::hasColumn('users', 'balance_days')) $table->dropColumn('balance_days');
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
        });
    }
};
