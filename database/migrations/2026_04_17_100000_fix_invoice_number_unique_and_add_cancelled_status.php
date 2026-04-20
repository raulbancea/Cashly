<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // 1. Sterge unique global pe number
            $table->dropUnique(['number']);

            // 2. Adauga unique composite (user_id, number)
            $table->unique(['user_id', 'number'], 'invoices_user_number_unique');
        });

        // 3. Actualizeaza enum status sa includa 'cancelled'
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_user_number_unique');
            $table->unique('number');
        });

        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft','sent','paid','overdue') NOT NULL DEFAULT 'draft'");
    }
};
