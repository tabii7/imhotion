<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('purchases')) {
            Schema::create('purchases', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->unsignedInteger('days');                // how many days bought
                $t->unsignedInteger('price_cents');         // total price in cents
                $t->enum('status', ['pending','paid','failed','refunded'])->default('pending');
                $t->string('provider')->nullable();         // mollie
                $t->string('provider_id')->nullable();      // mollie payment id
                $t->json('meta')->nullable();
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('purchases');
    }
};
