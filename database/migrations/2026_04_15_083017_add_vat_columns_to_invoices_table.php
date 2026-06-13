<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('vat_rate', 5, 2)->nullable()->default(null)->after('total');      
            $table->decimal('vat_amount', 10, 2)->default(0)->after('vat_rate');               
            $table->decimal('total_with_vat', 10, 2)->default(0)->after('vat_amount');         
        });
    }

    
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['vat_rate', 'vat_amount', 'total_with_vat']);
        });
    }
};
