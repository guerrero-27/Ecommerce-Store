<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id', 'change', 'reason', 'stock_after'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
