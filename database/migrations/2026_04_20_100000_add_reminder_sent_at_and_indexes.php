<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('notes');
            }
            if (!Schema::hasIndex('invoices', 'invoices_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
            $table->dropIndex(['user_id', 'status']);
        });
    }
};
