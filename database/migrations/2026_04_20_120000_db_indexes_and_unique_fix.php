<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DB-1: Drop DB-level unique constraint on (user_id, number) so soft-deleted
        // invoice numbers can be reused. Uniqueness is enforced at app level with
        // whereNull('deleted_at') in validation rules.
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_user_number_unique');
            // DB-2: Index for overdue command (due_date range queries)
            $table->index('due_date');
        });

        // DB-3: Index for report/dashboard queries on expenses date
        Schema::table('expenses', function (Blueprint $table) {
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['due_date']);
            $table->unique(['user_id', 'number'], 'invoices_user_number_unique');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
};
