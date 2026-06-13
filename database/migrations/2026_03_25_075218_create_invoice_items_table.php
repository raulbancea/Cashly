<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();                   
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();          
            $table->string('description')->nullable();                                           
            $table->decimal('quantity', 10, 2);                                                 
            $table->decimal('unit_price', 10, 2);                                               
            $table->decimal('total', 10, 2);                                                    
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
