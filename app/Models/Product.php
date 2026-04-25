<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'name',
        'cost_price',
        'price',
        'stock',
    ];


    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get the transaction details (line items) that reference this product.
     * Uses withTrashed() is not needed here — the relationship itself
     * doesn't filter by soft deletes. The Product model handles that.
     */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
