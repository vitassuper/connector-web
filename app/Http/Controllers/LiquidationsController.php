<?php

namespace App\Http\Controllers;

use App\Models\Liquidation;
use Illuminate\Http\Request;
use App\Models\ProcessedLiquidation;
use App\Actions\GetKlinesForRangeAction;

class LiquidationsController extends Controller
{
    public function __invoke(Request $request, GetKlinesForRangeAction $getKlinesForRangeAction)
    {
        $symbol = $request->input('symbol', 'BTCUSDT');
        $symbols = Liquidation::groupBy('symbol')->select('symbol')->get();

        $liquidations = ProcessedLiquidation::where('symbol', $symbol)->orderByDesc('created_at')->paginate(200);

        $klines = $getKlinesForRangeAction->execute(
            $symbol,
            $liquidations->getCollection()->last()->created_at->timestamp,
            $liquidations->getCollection()->first()->created_at->timestamp
        );

        $liquidations->getCollection()->each(function (ProcessedLiquidation $liquidation) use ($klines) {
            $kline = $klines->first(fn ($kline) => $kline['open_time'] === $liquidation->created_at->timestamp);

            if ($kline) {
                $liquidation->trade_volume = $kline['volume'];
                $liquidation->percentage = $liquidation->volume / $liquidation->trade_volume * 100;
            }
        });

        return view('liquidations', ['liquidations' => $liquidations, 'symbols' => $symbols, 'selected_symbol' => $symbol]);
    }
}
