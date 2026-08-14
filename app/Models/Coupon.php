<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(float $orderTotal): bool
    {
        if (!$this->is_active){
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()){
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit){
            return false;
        }

        if ($this->min_order_amount && $orderTotal < $this->min_order_amount){
            return flase;
        }

        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percentage'){
            return round($orderTotal * ($this->value / 100), 2);
        }

        return min($this->value, $orderTotal);
    }
}
