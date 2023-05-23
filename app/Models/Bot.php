<?php

namespace App\Models;

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
 * @property string|null $deleted_at
 * @property int|null $side
 * @property string|null $secret
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCopyBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereSide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bot extends Model
{
    use SoftDeletes;

    public const SHORT_SIDE = 0;
    public const LONG_SIDE = 1;

    protected $table = 'bots';

    protected $guarded = ['api_key', 'api_secret'];

    protected $attributes = [
        'enabled' => false
    ];

    public static function getAvailableSides(): array
    {
        return [
            self::LONG_SIDE => 'Long',
            self::SHORT_SIDE => 'Short',
        ];
    }

    public function copyBot(): BelongsTo
    {
        return $this->belongsTo(self::class, 'copy_bot_id');
    }

    public function getSideLabel(): string
    {
        return self::getAvailableSides()[$this->side];
    }
}
