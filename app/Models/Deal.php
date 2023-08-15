<?php

namespace App\Models;

use Exception;
use App\Enums\SideType;
use App\Enums\TransactionType;
use Illuminate\Support\Collection;
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
 * @property int|null $position
 * @property-read \App\Models\Bot|null $bot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
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
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereSafetyOrderCount($value)
 * @mixin \Eloquent
 */
class Deal extends Model
{
    protected $table = 'deals';

    protected $casts = [
        'date_close' => 'datetime:Y-m-d h:i:s',
        'date_open' => 'datetime:Y-m-d h:i:s'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'deal_id', 'id')->orderBy('created_at', 'desc');
    }

    private function getAveragePrice(Collection $orders): float|int
    {
        $totalValue = $orders->sum(fn (Order $order) => $order->price * $order->volume);
        $totalVolume = $orders->sum('volume');

        if (!$totalValue) {
            return 0;
        }

        return $totalValue / $totalVolume;
    }

    public function getOpenAveragePrice(): float|int
    {
        return $this->getAveragePrice($this->getOpenOrders());
    }

    public function getCloseAveragePrice(): float|int
    {
        return $this->getAveragePrice($this->getCloseOrders());
    }

    public function getCloseOrders(): Collection
    {
        $side = $this->getCloseTransactionType()->value;

        return $this->orders->filter(fn (Order $order) => $order->side === $side);
    }

    public function getOpenOrders(): Collection
    {
        $side = $this->getOpenTransactionType()->value;

        return $this->orders->filter(fn (Order $order) => $order->side === $side);
    }

    public function getPnl(): float|int
    {
        $openAveragePrice = $this->getOpenAveragePrice();
        $closeAveragePrice = $this->getCloseAveragePrice();

        $entrySum = $openAveragePrice * $this->getTotalVolume();
        $closeSum = $closeAveragePrice * $this->getTotalVolume();

        $sign = $this->bot->side->value ? 1 : -1;

        //TODO: refactor
        $fee = 0.04;

        $closeFee = $closeSum / 100 * $fee;
        $entryFee = $entrySum / 100 * $fee;

        return (($closeSum - $entrySum) * $sign) - $entryFee - $closeFee;
    }

    public function getPairName(): string
    {
        return str_replace(':USDT', '', $this->pair);
    }

    public function getOpenTransactionType(): TransactionType
    {
        if ($this->bot->side === SideType::Long) {
            return TransactionType::Buy;
        }

        if ($this->bot->side === SideType::Short) {
            return TransactionType::Sell;
        }

        throw new Exception('Unknown side');
    }

    public function getCloseTransactionType(): TransactionType
    {
        if ($this->bot->side === SideType::Long) {
            return TransactionType::Sell;
        }

        if ($this->bot->side === SideType::Short) {
            return TransactionType::Buy;
        }

        throw new Exception('Unknown side');
    }

    public function getTotalVolume()
    {
        return $this->getOpenOrders()
            ->sum(fn (Order $order) => $order->volume);
    }

    public function isClosed(): bool
    {
        return (bool) $this->date_close;
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id')->withTrashed()->withDefault(['name' => 'Unknown', 'id' => -1]);
    }
}
