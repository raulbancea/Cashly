<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();                                                         
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();       
            $table->string('name');                                               
            $table->string('cui')->nullable();                                    
            $table->string('email')->nullable();                                  
            $table->string('phone')->nullable();                                  
            $table->string('address')->nullable();                                
            $table->enum('status', ['active', 'prospect', 'inactive'])->default('prospect'); 
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
