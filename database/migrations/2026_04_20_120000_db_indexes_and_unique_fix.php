<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            
            
            
            $table->dropUnique('invoices_user_number_unique');

            
            $table->index('due_date');
        });

        
        Schema::table('expenses', function (Blueprint $table) {
            $table->index('date');
        });
    }

    
    public function down()
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
