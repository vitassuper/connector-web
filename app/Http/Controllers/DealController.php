<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Actions\GetPnlHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class DealController extends Controller
{
    public function index(GetPnlHistory $getPnlHistory): Factory|View|Application
    {
        $deals = Deal::orderBy('date_close', 'desc')->orderBy('date_open', 'desc')
            ->with(['orders'])->paginate(50);

        return view('deal.index', ['deals' => $deals, 'chartData' => $getPnlHistory->execute()]);
    }
}
