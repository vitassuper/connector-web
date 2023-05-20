<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCopyBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bot extends Model
{
    protected $table = 'bots';

    protected $guarded = ['api_key', 'api_secret'];

    protected $attributes = [
        'enabled' => false
    ];
}
