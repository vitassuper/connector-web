<?php

namespace App\Models;

use App\Enums\SideType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Bot
 *
 * @property int $id
 * @property bool $enabled
 * @property string $api_key
 * @property string $api_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property int|null $copy_bot_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property SideType|null $side
 * @property string|null $secret
 * @property int|null $exchange_id
 * @property-read Bot|null $copyBot
 * @property-read \App\Models\Exchange|null $exchange
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCopyBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereExchangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereSide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot withoutTrashed()
 * @mixin \Eloquent
 */
class Bot extends Model
{
    use SoftDeletes;

    protected $table = 'bots';

    protected $guarded = ['api_key', 'api_secret'];

    protected $attributes = [
        'enabled' => false,
    ];

    protected $casts = [
        'side' => SideType::class
    ];

    public function copyBot(): BelongsTo
    {
        return $this->belongsTo(self::class, 'copy_bot_id');
    }

    public static function getAvailableSides(): array
    {
        return [
            SideType::Long->value => SideType::Long->name,
            SideType::Short->value => SideType::Short->name,
        ];
    }

    public function getSideLabel(): string
    {
        return $this->side->value ? 'Long' : 'Short';
    }

    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class, 'exchange_id', 'id')
            ->withTrashed()->withDefault(['name' => 'Unknown', 'type' => 'Unknown']);
    }
}
