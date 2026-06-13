<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            
            
            $table->dropUnique(['number']);

            
            
            $table->unique(['user_id', 'number'], 'invoices_user_number_unique');
        });

        
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft'");
    }

    
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_user_number_unique');
            $table->unique('number');
        });

        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft','sent','paid','overdue') NOT NULL DEFAULT 'draft'");
    }
};
