<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Deal
 *
 * @property int $id
 * @property string $pair
 * @property int $safety_order_count
 * @property string $date_open
 * @property \Illuminate\Support\Carbon|null $date_close
 * @property string|null $pnl
 * @property int $bot_id
 * @property-read \App\Models\Bot|null $bot
 * @property-read int|float $average_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDateClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDateOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePnl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereSafetyOrderCount($value)
 * @mixin \Eloquent
 */
class Deal extends Model
{
    protected $table = 'deals';

    protected $casts = ['date_close' => 'datetime'];

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

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id')->withDefault(['name' => 'Unknown', 'id' => -1]);
    }
}
