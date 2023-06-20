<?php

namespace App\Http\Controllers\Bots;

use App\Enums\ExchangeType;
use Illuminate\Http\Request;
use App\Actions\GetBotsAction;
use App\Http\Resources\BotResource;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiBotController extends Controller
{
    public function index(Request $request, GetBotsAction $getBotsAction): AnonymousResourceCollection
    {
        $request->validate([
            'exchange_type' => [new Enum(ExchangeType::class)],
        ]);

        return BotResource::collection($getBotsAction->execute(ExchangeType::tryFrom($request->input('exchange_type'))));
    }
}
