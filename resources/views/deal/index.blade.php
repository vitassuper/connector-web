@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="w-100">
                        <canvas id="myChart"></canvas>
                    </div>

                    <div class="card-header">Filters</div>
                    <div class="card-body">
                        <form action="{{route('deals.index')}}">
                            <div class="row">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label for="deal_id" class="col-md-4 col-form-label">Deal Id</label>
                                    <input class="form-control" value="{{$filters['deal_id'] ?? ''}}" name="deal_id"
                                           id="deal_id"/>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label for="status" class="col-md-4 col-form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">None</option>
                                        @foreach(['active', 'closed'] as $status)
                                            <option
                                                @if(isset($filters['status']) ? $filters['status'] === $status : null) selected
                                                @endif value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label for="bot_id" class="col-md-4 col-form-label">Bot Id</label>
                                    <select name="bot_id" id="bot_id" class="form-select">
                                        <option value="">None</option>
                                        @foreach($bots as $bot)
                                            <option
                                                @if(isset($filters['bot_id']) ? (int) $filters['bot_id'] === $bot->id : null) selected
                                                @endif value="{{$bot->id}}">{{$bot->name}}({{$bot->id}})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label for="pair" class="col-md-4 col-form-label">Pair</label>
                                    <select name="pair" id="pair" class="form-select">
                                        <option value="">None</option>
                                        @foreach($pairs as $pair)
                                            <option
                                                @if(isset($filters['pair']) ? $filters['pair'] === $pair->pair : null) selected
                                                @endif value="{{$pair->pair}}">{{$pair->getPairName()}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <button class="btn btn-success my-2" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Deals</div>
                    <div class="card-body">
                        @if($deals->count())
                            <div class="table-responsive">
                                <table class="table table-sm deals-table table-bordered">
                                    <thead class="sticky-top table-secondary">
                                    <tr>
                                        <th>Id</th>
                                        <th>Pair</th>
                                        <th>Position</th>
                                        <th>Bot</th>
                                        <th>Safety orders</th>
                                        <th>PNL</th>
                                        <th>Unrealized PNL</th>
                                        <th>Date open</th>
                                        <th>Date close</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    @foreach($deals as $deal)
                                        <tr>
                                            <td>{{$deal->id}}</td>
                                            <td>{{$deal->getPairName()}}</td>
                                            <td>{{$deal->position}}</td>
                                            <td>{{$deal->bot->name}}({{$deal->bot->id}})</td>
                                            <td>{{$deal->safety_order_count}}</td>
                                            <td>{{$deal->date_close ? $deal->getPnl() : ''}}</td>
                                            <td class="@if($deal->uPnl > 0) text-success @endif @if($deal->uPnl < 0) text-danger @endif">@if(!is_null($deal->uPnl))
                                                    {{$deal->uPnl}} ({{$deal->uPnlPercentage}}%)
                                                @endif</td>
                                            <td>{{$deal->date_open}}</td>
                                            <td>{{$deal->date_close}}</td>
                                            <td>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-info expander me-2">Expand
                                                    </button>
                                                    @if(!$deal->isClosed())
                                                        <button type="button"
                                                                class="btn btn-success addSOModalButton me-2"
                                                                data-attr="{{ route('deals.add', $deal)}}">Add SO
                                                        </button>
                                                        <button type="button" class="btn btn-danger formModalButton"
                                                                data-attr="{{ route('deals.close', $deal) }}">Close
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display: none">
                                            <td colspan="9">
                                                <table class="table table-striped">
                                                    <p>Average price: {{$deal->getOpenAveragePrice()}}</p>
                                                    <p>Total volume: {{$deal->getTotalVolume()}}</p>
                                                    <thead>
                                                    <tr>
                                                        <th>Order Id</th>
                                                        <th>Side</th>
                                                        <th>Price</th>
                                                        <th>Volume</th>
                                                        <th>Created at</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($deal->orders as $order)
                                                        <tr>
                                                            <td>{{$order->id}}</td>
                                                            <td class="{{$order->side === 'sell' ? 'text-danger' : 'text-success'}}">{{$order->side}}</td>
                                                            <td>{{$order->price}}</td>
                                                            <td>{{$order->volume}}</td>
                                                            <td>{{$order->created_at}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="col-md-8">
                                <span>There are no deals yet</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">{{$deals->links()}}</div>
                </div>
            </div>
        </div>
    </div>
    @include('bot.add_safety_order')
    @include('components.modal_form', ['modalBtn' => '<button type="submit" class="btn btn-danger">Close</button>'])
    <script type="module">
        $('.expander').click(function () {
            $(this).closest('tr').next().toggle();
        })
    </script>
    <script src="
https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js
"></script>
    <script>
        const ctx = document.getElementById('myChart');

        const chartData = @json($chartData);
        const labels = Object.keys(chartData);
        const values = Object.values(chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Profit',
                    backgroundColor: values.map(value => value >= 0 ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'),
                    data: values,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                }
            }
        });

    </script>
@endsection
