<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class HomeController extends Controller
{
    public function index(): Factory|View|Application
    {
        $totalProfit = Deal::sum('pnl');
        $dailyProfit = Deal::whereDate('date_close', now()->toDateString())->sum('pnl');

        return view('home', ['daily_pnl' => $dailyProfit, 'total_pnl' => $totalProfit]);
    }
}
