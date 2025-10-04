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
            if (! Schema::hasColumn('purchases', 'pricing_item_id')) {
                // make nullable to avoid invalid default 0 for existing rows
                $table->unsignedBigInteger('pricing_item_id')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('purchases', 'amount')) {
                $table->decimal('amount', 10, 2)->after('pricing_item_id');
            }

            if (! Schema::hasColumn('purchases', 'currency')) {
                $table->string('currency', 3)->default('EUR')->after('amount');
            }

            if (! Schema::hasColumn('purchases', 'status')) {
                $table->string('status')->default('pending')->after('currency');
            }

            if (! Schema::hasColumn('purchases', 'mollie_payment_id')) {
                $table->string('mollie_payment_id')->nullable()->after('status');
            }

            // Add foreign keys if not present
            try {
                if (! $this->hasForeignKey('purchases', 'purchases_user_id_foreign')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
            } catch (\Exception $e) {
                // Foreign key might already exist, ignore the error
                \Log::info('Foreign key user_id might already exist: ' . $e->getMessage());
            }

            // Clean up any invalid pricing_item_id values (0 or non-existent) before adding FK
            try {
                \Illuminate\Support\Facades\DB::table('purchases')
                    ->where('pricing_item_id', 0)
                    ->update(['pricing_item_id' => null]);

                // If there are any pricing_item_id values that don't exist in pricing_items, set them to null
                \Illuminate\Support\Facades\DB::statement(
                    'UPDATE purchases p LEFT JOIN pricing_items pi ON p.pricing_item_id = pi.id SET p.pricing_item_id = NULL WHERE p.pricing_item_id IS NOT NULL AND pi.id IS NULL'
                );

                if (! $this->hasForeignKey('purchases', 'purchases_pricing_item_id_foreign')) {
                    $table->foreign('pricing_item_id')->references('id')->on('pricing_items')->onDelete('cascade');
                }
            } catch (\Exception $e) {
                // If adding the FK still fails, leave the column nullable and proceed
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
            foreach (['pricing_item_id','amount','currency','status','mollie_payment_id'] as $c) {
                if (Schema::hasColumn('purchases', $c)) {
                    $cols[] = $c;
                }
            }

            if (count($cols)) {
                $table->dropColumn($cols);
            }
        });
    }

    // helper to attempt checking foreign key existence
    protected function hasForeignKey(string $table, string $foreignName): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails($table);
            return $doctrineTable->hasForeignKey($foreignName);
        } catch (\Exception $e) {
            return false;
        }
    }
};
