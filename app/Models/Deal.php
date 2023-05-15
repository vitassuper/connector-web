<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Deal extends Model
{
    protected $table = 'deals';

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'deal_id', 'id')->orderBy('created_at', 'desc');
    }

    public function getAveragePriceAttribute(): float|int
    {
        $orders = $this->orders;

        $totalValue = $orders->sum(fn (Order $order) => $order->price * $order->volume);
        $totalVolume = $orders->sum('volume');

        if (!$totalValue) {
            return 0;
        }

        return $totalValue / $totalVolume;
    }
}
