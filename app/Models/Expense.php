<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToUser;

class Expense extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'category_id',
        'description',
        'amount',
        'currency',
        'date',
        'receipt_path',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
