<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserFactory extends Factory
{
    
    protected static $password;

    
    public function definition()
    {
        return [
            'name'              => fake()->name(),             
            'email'             => fake()->unique()->safeEmail(), 
            'email_verified_at' => now(),                      
            'password'          => static::getPassword(),      
            'remember_token'    => Str::random(10),            
        ];
    }

    
    protected static function getPassword()
    {
        if (static::$password === null) {
            static::$password = Hash::make('password');
        }
        return static::$password;
    }

    
    public function unverified()
    {
        return $this->state(function ($attributes) {
            return ['email_verified_at' => null];
        });
    }
}
