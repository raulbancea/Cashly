<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'company_name',
        'company_vat',
        'phone',
        'address',
        'currency',
        'plan',
        'logo',
        'bank_account',
        'trial_ends_at',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_status',
        'subscription_ends_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'password'             => 'hashed',
        'trial_ends_at'        => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function hasActiveSubscription()
    {

        if ($this->trial_ends_at !== null && $this->trial_ends_at->isFuture()) {
            return true;
        }

        if (in_array($this->subscription_status, ['active', 'trialing'])) {
            if ($this->subscription_ends_at !== null && $this->subscription_ends_at->isFuture()) {
                return true;
            }
        }

        return false;
    }

    public function isOnTrial()
    {

        if ($this->trial_ends_at !== null && $this->trial_ends_at->isFuture() && $this->stripe_subscription_id === null) {
            return true;
        }

        return false;
    }

    public function trialDaysLeft()
    {

        if ($this->trial_ends_at === null) {
            return 0;
        }

        $zileDiferenta = (int) now()->diffInDays($this->trial_ends_at, false);

        if ($zileDiferenta < 0) {
            return 0;
        }

        return $zileDiferenta;
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }
}
