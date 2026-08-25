<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'guest_name', 'guest_email',
        'status', 'subtotal', 'discount', 'total', 'cuopon_id', 'shipping_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::Class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function customerName(): string
    {
        return $this->user?->nmae ?? $this->guest_name ?? 'Guest';
    }

    public function statusClasses(): string
    {
        return match ($this->status) {
            'pending' => 'text-yellow-700 bg-yellow-50',
            'paid' => 'text-blue-700 bg-blue-50',
            'processing' => 'text-indigo-700 bg-indigo-50',
            'shipped' => 'text-purple-700 bg-purple-50',
            'delivered' => 'text-green-700 bg-green-50',
            'cancelled' => 'text-red-700 bg-red-50',
            default => 'text-gray-700 bg-gray-50',
        };
    }
}
