<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL: alter enum to include 'requesting'
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('new','processing','shipped','delivered','cancelled','requesting') NOT NULL DEFAULT 'new'");
        } else {
            // Other drivers (sqlite, pg): attempt a safe change to string if possible
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->string('status')->default('new')->change();
                });
            } catch (\Throwable $e) {
                // swallow - some environments may not support altering column types without doctrine/dbal
                logger()->warning('Could not alter orders.status column to string: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('new','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'new'");
        } else {
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->enum('status', ['new','processing','shipped','delivered','cancelled'])->default('new')->change();
                });
            } catch (\Throwable $e) {
                logger()->warning('Could not revert orders.status column change: ' . $e->getMessage());
            }
        }
    }
};
