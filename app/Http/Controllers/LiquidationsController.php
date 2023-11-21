<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Liquidation;
use Illuminate\Http\Request;

class LiquidationsController extends Controller
{
    public function __invoke(Request $request)
    {
        $symbol = $request->input('symbol', 'BTCUSDT');
        $liquidations = Liquidation::where('symbol', $symbol)->get();

        $symbols = Liquidation::groupBy('symbol')->select('symbol')->get();

        $liquidations = $liquidations->groupBy(function ($item) {
            // Calculate the five-minute interval using Carbon
            $fiveMinuteInterval = floor($item->created_at->timestamp / 300) * 300;

            // Group by symbol and the calculated five-minute interval
            return $item->symbol . '-' . $fiveMinuteInterval;
        })->map(function ($group, $key) {
            // Perform aggregation for each group
            [$symbol, $intervalTimestamp] = explode('-', $key);
            $totalVolume = $group->sum('quantity');

            return (object) [
                'symbol' => $symbol,
                'total_volume' => $totalVolume,
                'created_at' => Carbon::createFromTimestamp($intervalTimestamp),
            ];
        })->sortBy(function ($item) {
            return $item->created_at->timestamp; // Sort by the interval timestamp
        })->values();

        return view('liquidations', ['liquidations' => $liquidations, 'symbols' => $symbols, 'selected_symbol' => $symbol]);
    }
}
