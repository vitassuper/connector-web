<?php

namespace App\Http\Controllers\Exchanges;

use App\Models\Exchange;
use App\Base\BaseController;
use App\DataObjects\ExchangeData;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use App\Actions\CreateExchangeAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Requests\CreateExchangeRequest;
use Illuminate\Contracts\Foundation\Application;

class ExchangeController extends BaseController
{
    public function index(): Factory|View|Application
    {
        $exchanges = $this->getCurrentUser()->exchanges()->get();

        return view('exchange.index', ['exchanges' => $exchanges]);
    }

    public function create(): Factory|View|Application
    {
        return view('exchange.form');
    }

    public function store(CreateExchangeRequest $request, CreateExchangeAction $createExchangeAction): Redirector|Application|RedirectResponse
    {
        $createExchangeAction->execute(ExchangeData::fromRequests($request), $this->getCurrentUser());

        return redirect(route('home'));
    }

    public function destroy(Exchange $exchange): Redirector|Application|RedirectResponse
    {
        $exchange->delete();

        return redirect(route('exchanges.index'));
    }
}
