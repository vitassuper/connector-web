<?php

namespace App\Models;

use App\Helpers\CryptHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Exchange
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $user_id
 * @property string $api_key
 * @property string $api_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 */
class Exchange extends Model
{
    public const BINANCE_TYPE = 'binance';
    public const OKEX_TYPE = 'okex';

    protected $table = 'exchanges';

    protected $guarded = ['user_id', 'api_key', 'api_secret'];

    public static function getAvailableTypes(): array
    {
        return [
            self::BINANCE_TYPE,
            self::OKEX_TYPE
        ];
    }

    public function setApiKey(string $value): void
    {
        $this->api_key = CryptHelper::encrypt($value);
    }

    public function getApiKeyShard(): string
    {
        return substr(CryptHelper::decrypt($this->api_key), -6);
    }

    public function setApiSecret(string $value): void
    {
        $this->api_secret = CryptHelper::encrypt($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
