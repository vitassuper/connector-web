<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Liquidation
 *
 * @property int $id
 * @property string $symbol
 * @property string $side
 * @property string $quantity
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereSide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereSymbol($value)
 * @mixin \Eloquent
 */
class Liquidation extends Model
{
}
