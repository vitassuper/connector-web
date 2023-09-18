<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Bot;
use App\Models\Deal;
use Illuminate\Http\Request;
use App\Actions\GetBotsStats;
use App\Actions\GetPnlHistory;
use App\Actions\GetDealsAction;
use Illuminate\Validation\Rule;
use App\Actions\UpdateDealAction;
use App\Requests\ListDealsRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\DataObjects\DealFiltersObject;
use Illuminate\Contracts\View\Factory;
use App\Actions\SendAddDealRequestAction;
use App\Actions\SendCloseDealRequestAction;
use App\Requests\AddSafetyOrderToDealRequest;
use Illuminate\Contracts\Foundation\Application;

class DealController extends Controller
{
    public function index(
        ListDealsRequest $request,
        GetBotsStats $getBotsStats,
        GetDealsAction $getDealsAction,
        GetPnlHistory $getPnlHistory
    ): Factory|View|Application {
        $data = DealFiltersObject::fromRequest($request);

        $pairs = Deal::groupBy('pair')->select('pair')->get();

        $botsStats = $getBotsStats->execute($data);
        $deals = $getDealsAction->execute($data);
        $filters = $request->validated();

        $deals->appends($filters);

        return view('deal.index', ['deals' => $deals,
            'filters' => $filters, 'bots' => Bot::get(),
            'pairs' => $pairs,
            'botsStats' => $botsStats,
            'chartData' => $getPnlHistory->execute()]);
    }

    public function add(
        Deal $deal,
        AddSafetyOrderToDealRequest $request,
        SendAddDealRequestAction $sendAddDealRequestAction
    ): Redirector|Application|RedirectResponse {
        $result = $sendAddDealRequestAction->execute($deal, $request->input('amount'));

        $view = redirect(route('deals.index', $request->query()));

        if ($result) {
            $view->with('success', 'Deal was successfully averaged');
        } else {
            $view->with('fail', 'Deal was not averaged');
        }

        return $view;
    }

    public function update(
        Deal $deal,
        Request $request,
        UpdateDealAction $updateDealAction
    ): Redirector|Application|RedirectResponse {
        $validator = Validator::make($request->all(), ['pos_number' => [
            'required',
            'numeric',
            Rule::unique(Deal::class, 'position')
                ->whereNull('date_close')
                ->where('pair', $deal->pair)
                ->where('bot_id', $deal->bot_id),
        ]]);

        $view = redirect(route('deals.index', $request->query()));

        if ($validator->fails()) {
            return $view->with('fail', 'Incorrect position number');
        }

        $updateDealAction->execute($deal, $request->input('pos_number'));

        return $view->with('success', 'Deal was successfully changed');
    }

    public function close(Deal $deal, Request $request, SendCloseDealRequestAction $sendCloseDealRequestAction): Redirector|Application|RedirectResponse
    {
        $result = $sendCloseDealRequestAction->execute($deal);

        $view = redirect(route('deals.index', $request->query()));

        if ($result) {
            $view->with('success', 'Deal was successfully closed');
        } else {
            $view->with('fail', 'Deal was not closed');
        }

        return $view;
    }
}
