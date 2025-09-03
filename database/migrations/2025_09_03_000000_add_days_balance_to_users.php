<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasColumn('users', 'days_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('days_balance')->default(0)->after('remember_token');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'days_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('days_balance');
            });
        }
    }
};
