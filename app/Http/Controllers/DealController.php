<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Actions\GetPnlHistory;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Actions\SendAddDealRequestAction;
use App\Actions\SendCloseDealRequestAction;
use App\Requests\AddSafetyOrderToDealRequest;
use Illuminate\Contracts\Foundation\Application;

class DealController extends Controller
{
    public function index(GetPnlHistory $getPnlHistory): Factory|View|Application
    {
        $deals = Deal::orderBy('date_close', 'desc')->orderBy('date_open', 'desc')
            ->with(['orders'])->paginate(50);

        return view('deal.index', ['deals' => $deals, 'chartData' => $getPnlHistory->execute()]);
    }

    public function add(
        Deal $deal,
        AddSafetyOrderToDealRequest $request,
        SendAddDealRequestAction $sendAddDealRequestAction
    ): Redirector|Application|RedirectResponse {
        $result = $sendAddDealRequestAction->execute($deal, $request->input('amount'));

        $view = redirect(route('deals.index'));

        if ($result) {
            $view->with('success', 'Deal was successfully averaged');
        } else {
            $view->with('fail', 'Deal was not averaged');
        }

        return $view;
    }

    public function close(Deal $deal, SendCloseDealRequestAction $sendCloseDealRequestAction): Redirector|Application|RedirectResponse
    {
        $result = $sendCloseDealRequestAction->execute($deal);

        $view = redirect(route('deals.index'));

        if ($result) {
            $view->with('success', 'Deal was successfully closed');
        } else {
            $view->with('fail', 'Deal was not closed');
        }

        return $view;
    }
}
