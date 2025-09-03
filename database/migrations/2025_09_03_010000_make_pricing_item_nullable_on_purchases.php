<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Use raw ALTER TABLE to change column nullability. This avoids attempting to drop non-existent FK names.
        if (Schema::hasTable('purchases') && Schema::hasColumn('purchases', 'pricing_item_id')) {
            // Make nullable
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `purchases` MODIFY `pricing_item_id` BIGINT UNSIGNED NULL');
        }
    }

    public function down()
    {
        if (Schema::hasTable('purchases') && Schema::hasColumn('purchases', 'pricing_item_id')) {
            // Revert to NOT NULL
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `purchases` MODIFY `pricing_item_id` BIGINT UNSIGNED NOT NULL');
        }
    }
};
