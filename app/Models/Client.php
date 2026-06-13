<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToUser;


class Client extends Model
{
    use HasFactory, BelongsToUser;

    
    protected $fillable = [
        'user_id',
        'name',
        'cui',
        'email',
        'phone',
        'address',
        'website',
        'avatar',
        'status',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
