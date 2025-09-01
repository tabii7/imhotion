<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'full_name'))    $t->string('full_name')->nullable();
            if (!Schema::hasColumn('users', 'address'))      $t->string('address')->nullable();
            if (!Schema::hasColumn('users', 'postal_code'))  $t->string('postal_code', 20)->nullable();
            if (!Schema::hasColumn('users', 'city'))         $t->string('city')->nullable();
            if (!Schema::hasColumn('users', 'country'))      $t->string('country', 2)->nullable();
            if (!Schema::hasColumn('users', 'phone'))        $t->string('phone', 40)->nullable();
            if (!Schema::hasColumn('users', 'role'))         $t->enum('role', ['admin','client'])->default('client')->index();
            if (!Schema::hasColumn('users', 'balance_days')) $t->unsignedInteger('balance_days')->default(0);
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $t) {
            if (Schema::hasColumn('users', 'full_name'))    $t->dropColumn('full_name');
            if (Schema::hasColumn('users', 'address'))      $t->dropColumn('address');
            if (Schema::hasColumn('users', 'postal_code'))  $t->dropColumn('postal_code');
            if (Schema::hasColumn('users', 'city'))         $t->dropColumn('city');
            if (Schema::hasColumn('users', 'country'))      $t->dropColumn('country');
            if (Schema::hasColumn('users', 'phone'))        $t->dropColumn('phone');
            if (Schema::hasColumn('users', 'role'))         $t->dropColumn('role');
            if (Schema::hasColumn('users', 'balance_days')) $t->dropColumn('balance_days');
        });
    }
};
