<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Saving;

class Order extends Model
{
    /**
     * Timestamps to be set.
     *
     * @var string[]
     */
    public $timestamps = ["created_at"];

    /**
     * Disable model update timestamp.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer', 'phone', 'user_id', 'type', 'status'];

    use HasFactory;
    use Saving;

    /**
     * Get the user who made the order.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products from the order.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items');
    }

    /**
     * A mutator that removes unnecessary characters from the phone number field
     *
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/\D/', '', $value);
    }
}
