<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function index(): Renderable
    {
        $totalProfit = Deal::sum('pnl');
        $dailyProfit = Deal::whereDate('date_close', now()->toDateString())->sum('pnl');

        return view('home', ['daily_pnl' => $dailyProfit, 'total_pnl' => $totalProfit]);
    }
}
