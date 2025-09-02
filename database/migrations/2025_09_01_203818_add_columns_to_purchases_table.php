<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (! Schema::hasColumn('purchases', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }

            if (! Schema::hasColumn('purchases', 'pricing_item_id')) {
                $table->foreignId('pricing_item_id')->constrained()->onDelete('cascade');
            }

            if (! Schema::hasColumn('purchases', 'amount')) {
                $table->decimal('amount', 10, 2);
            }

            if (! Schema::hasColumn('purchases', 'currency')) {
                $table->string('currency', 3)->default('EUR');
            }

            if (! Schema::hasColumn('purchases', 'status')) {
                $table->string('status')->default('pending');
            }

            if (! Schema::hasColumn('purchases', 'mollie_payment_id')) {
                $table->string('mollie_payment_id')->nullable();
            }

            if (! Schema::hasColumn('purchases', 'payment_data')) {
                $table->json('payment_data')->nullable();
            }

            if (! Schema::hasColumn('purchases', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'user_id')) {
                try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
            }

            if (Schema::hasColumn('purchases', 'pricing_item_id')) {
                try { $table->dropForeign(['pricing_item_id']); } catch (\Exception $e) {}
            }

            $cols = [];
            foreach (['user_id','pricing_item_id','amount','currency','status','mollie_payment_id','payment_data','paid_at'] as $c) {
                if (Schema::hasColumn('purchases', $c)) {
                    $cols[] = $c;
                }
            }

            if (count($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
