<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (! Schema::hasColumn('purchases', 'credited')) {
                $table->boolean('credited')->default(false)->after('status');
            }

            if (! Schema::hasColumn('purchases', 'payment_data')) {
                $table->text('payment_data')->nullable()->after('mollie_payment_id');
            }

            if (! Schema::hasColumn('purchases', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_data');
            }
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'credited')) {
                $table->dropColumn('credited');
            }
            if (Schema::hasColumn('purchases', 'payment_data')) {
                $table->dropColumn('payment_data');
            }
            if (Schema::hasColumn('purchases', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
