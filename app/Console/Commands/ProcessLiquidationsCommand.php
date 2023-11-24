<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Liquidation;
use Illuminate\Console\Command;
use App\Models\ProcessedLiquidation;

class ProcessLiquidationsCommand extends Command
{
    protected $description = 'Process liquidations';

    protected $signature = 'process:liquidations';

    public function handle(): void
    {
        //TODO: Cache it
        $symbols = Liquidation::groupBy('symbol')->whereNot('symbol', 'USDCUSDT')
            ->select('symbol')->pluck('symbol');

        foreach ($symbols as $symbol) {
            $lastProcessedLiquidation = ProcessedLiquidation::where('symbol', $symbol)
                ->orderBy('created_at', 'desc')->first();

            if (!$lastProcessedLiquidation) {
                //TODO: Use chunks for saving memory
                $liquidations = Liquidation::where('symbol', $symbol)->get();
            } else {
                $liquidations = Liquidation::where('created_at', '>=', $lastProcessedLiquidation->created_at)->where('symbol', $symbol)->get();

                $lastProcessedLiquidation->delete();
            }

            $this->processLiquidations($symbol, $liquidations);

            $this->info('Finished: ' . $symbol);
        }
    }

    private function processLiquidations(string $symbol, $liquidations): void
    {
        $liquidations->groupBy(function (Liquidation $item) {
            // Calculate the minute interval using Carbon
            $minuteInterval = floor($item->created_at->timestamp / 60) * 60;

            return $item->side . '-' . $minuteInterval;
        })->each(function ($group, $key) use ($symbol) {
            [$side, $intervalTimestamp] = explode('-', $key);
            $totalVolume = $group->sum('quantity');

            ProcessedLiquidation::create([
                'symbol' => $symbol,
                'side' => $side,
                'volume' => $totalVolume,
                'created_at' => Carbon::createFromTimestamp($intervalTimestamp),
            ]);
        });
    }
}
