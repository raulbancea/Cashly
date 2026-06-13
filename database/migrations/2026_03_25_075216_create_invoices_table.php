<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();                                                              
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();            
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();          
            $table->string('number')->unique();                                        
            $table->date('issue_date');                                                
            $table->date('due_date')->nullable();                                      
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft'); 
            $table->decimal('total', 10, 2)->default(0);                               
            $table->enum('currency', ['EUR', 'RON'])->default('RON');                  
            $table->string('pdf_path')->nullable();                                    
            $table->text('notes')->nullable();                                         
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
