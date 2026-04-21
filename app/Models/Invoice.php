<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToUser;

class Invoice extends Model
{
    use HasFactory, BelongsToUser, SoftDeletes;
    protected $fillable = [
        'user_id',
        'client_id',
        'number',
        'issue_date',
        'due_date',
        'status',
        'total',
        'vat_rate',
        'vat_amount',
        'total_with_vat',
        'currency',
        'pdf_path',
        'notes',
        'reminder_sent_at',
    ];

    protected $casts = [
        'issue_date'      => 'date',
        'due_date'        => 'date',
        'total'           => 'decimal:2',
        'vat_rate'        => 'decimal:2',
        'vat_amount'      => 'decimal:2',
        'total_with_vat'  => 'decimal:2',
        'reminder_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getEffectiveTotalAttribute(): float
    {
        return $this->vat_rate ? (float) $this->total_with_vat : (float) $this->total;
    }
}
