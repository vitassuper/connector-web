<?php

namespace App\Http\Controllers\Bots;

use App\Models\Bot;
use App\DataObjects\BotData;
use App\Actions\CreateBotAction;
use App\Actions\UpdateBotAction;
use App\Requests\CreateBotRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class BotController extends Controller
{
    public function index(): string
    {
        $bots = Bot::orderBy('id', 'asc')->paginate(100);

        return view('bot.index', ['bots' => $bots])->render();
    }

    public function create(): string
    {
        return view('bot.form', ['bot' => new Bot()])->render();
    }

    public function edit(Bot $bot): string
    {
        return view('bot.form', ['bot' => $bot])->render();
    }

    public function store(CreateBotRequest $request, CreateBotAction $createBotAction): Redirector|Application|RedirectResponse
    {
//        $createBotAction->execute(BotData::createFromRequest($request));

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

    public function delete(Bot $bot): Factory|View|Application
    {
        return view('components.delete', ['url' => route('bots.destroy', $bot)]);
    }

    public function destroy(Bot $bot)
    {
//        $bot->delete();

        return redirect(route('bots.index'));
    }
}
