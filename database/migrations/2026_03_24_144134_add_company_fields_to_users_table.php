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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('company_vat')->nullable(); // CUI in Romania
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('currency')->default('EUR');
            $table->string('plan')->default('free');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                    'company_name',
                    'company_vat',
                    'phone',
                    'address',
                    'currency',
                    'plan'
            ]);
        });
    }
};
