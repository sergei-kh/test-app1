<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    use HasFactory;

    /**
     * Get orders with this product.
     *
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this
            ->belongsToMany(Order::class, 'order_items')
            ->withPivot('count', 'discount', 'cost');
    }
}
