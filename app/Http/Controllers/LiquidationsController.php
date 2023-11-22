<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Liquidation;
use Illuminate\Http\Request;
use App\Actions\GetKlinesForRangeAction;

class LiquidationsController extends Controller
{
    public function __invoke(Request $request, GetKlinesForRangeAction $getKlinesForRangeAction)
    {
        $symbol = $request->input('symbol', 'BTCUSDT');

        $liquidations = Liquidation::where('symbol', $symbol)->get();

        $symbols = Liquidation::groupBy('symbol')->select('symbol')->get();

        $liquidations = $liquidations->groupBy(function (Liquidation $item) {
            // Calculate the five-minute interval using Carbon
            $fiveMinuteInterval = floor($item->created_at->timestamp / 300) * 300;

            // Group by symbol and the calculated five-minute interval
            return $item->symbol . '-' . $item->side . '-' . $fiveMinuteInterval;
        })->map(function ($group, $key) {
            // Perform aggregation for each group
            [$symbol, $side, $intervalTimestamp] = explode('-', $key);
            $totalVolume = $group->sum('quantity');

            return (object) [
                'symbol' => $symbol,
                'side' => $side,
                'total_volume' => $totalVolume,
                'created_at' => Carbon::createFromTimestamp($intervalTimestamp),
            ];
        })->sortBy(function ($item) {
            return $item->created_at->timestamp; // Sort by the interval timestamp
        })->values();

        $klines = collect($getKlinesForRangeAction->execute($symbol, $liquidations->first()->created_at->timestamp, $liquidations->last()->created_at->timestamp));

        $liquidations->each(function ($liquidation) use ($klines) {
            $kline = $klines->first(fn ($kline) => $kline[0] / 1000 === $liquidation->created_at->timestamp);
            if($kline) {
                $liquidation->trade_volume = $kline[5];
                $liquidation->percentage = $liquidation->total_volume / $liquidation->trade_volume * 100;
            }
        });

        return view('liquidations', ['liquidations' => $liquidations, 'symbols' => $symbols, 'selected_symbol' => $symbol]);
    }
}
