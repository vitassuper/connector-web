<?php

namespace App\Http\Controllers\Bots;

use App\Models\Bot;
use App\Base\BaseController;
use App\DataObjects\BotData;
use App\Actions\CreateBotAction;
use App\Actions\UpdateBotAction;
use App\Requests\CreateBotRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class BotController extends BaseController
{
    public function index(): Factory|View|Application
    {
        $bots = Bot::orderBy('id')->paginate(100);

        return view('bot.index', ['bots' => $bots]);
    }

    public function create(): Factory|View|Application
    {
        $exchanges = $this->getCurrentUser()->exchanges()->get();

        return view('bot.form', ['bot' => new Bot(), 'exchanges' => $exchanges]);
    }

    public function edit(Bot $bot): Factory|View|Application
    {
        $exchanges = $this->getCurrentUser()->exchanges()->get();

        return view('bot.form', ['bot' => $bot, 'exchanges' => $exchanges]);
    }

    public function store(CreateBotRequest $request, CreateBotAction $createBotAction): Redirector|Application|RedirectResponse
    {
        $createBotAction->execute(BotData::createFromRequest($request));

        return redirect(route('bots.index'));
    }

    public function update(Bot $bot, CreateBotRequest $request, UpdateBotAction $updateBotAction): Redirector|Application|RedirectResponse
    {
        $updateBotAction->execute($bot, BotData::createFromRequest($request));

        return redirect(route('bots.index'));
    }

    public function show(Bot $bot): Redirector|Application|RedirectResponse
    {
        return redirect(route('bots.index'));
    }

    public function toggle(Bot $bot): Redirector|Application|RedirectResponse
    {
        $bot->update(['enabled' => !$bot->enabled]);

        return redirect(route('bots.index'));
    }

    public function destroy(Bot $bot): Redirector|Application|RedirectResponse
    {
        $bot->delete();

        return redirect(route('bots.index'));
    }
}
