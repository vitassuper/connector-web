<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProcessedLiquidation
 *
 * @property int $id
 * @property string $symbol
 * @property string $side
 * @property string $volume
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation whereSide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedLiquidation whereVolume($value)
 * @mixin \Eloquent
 */
class ProcessedLiquidation extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $dates = [
        'created_at',
    ];
}
