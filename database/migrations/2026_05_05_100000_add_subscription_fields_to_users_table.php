<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('plan');                          
            $table->string('stripe_customer_id')->nullable()->after('trial_ends_at');              
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');    
            $table->string('subscription_status')->nullable()->after('stripe_subscription_id');   
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_status');  
        });
    }

    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'trial_ends_at',
                'stripe_customer_id',
                'stripe_subscription_id',
                'subscription_status',
                'subscription_ends_at',
            ]);
        });
    }
};
