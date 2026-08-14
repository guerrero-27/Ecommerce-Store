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

    public function statusColor(): string
    {
        return match ($this->status){
            'pending' => 'yellow',
            'paid' => 'blue',
            'processing' => 'indigo',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
