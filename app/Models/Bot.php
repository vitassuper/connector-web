<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';

    protected $guarded = ['api_key', 'api_secret'];
}
