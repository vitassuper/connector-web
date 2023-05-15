@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Deals</div>
                    <div class="card-body">
                        @if($deals->count())
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Pair</th>
                                    <th>Bot Id</th>
                                    <th>Safety orders</th>
                                    <th>PNL</th>
                                    <th>Date open</th>
                                    <th>Date close</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($deals as $deal)
                                        <tr>
                                            <td>{{$deal->id}}</td>
                                            <td>{{$deal->pair}}</td>
                                            <td>{{$deal->bot_id}}</td>
                                            <td>{{$deal->safety_order_count}}</td>
                                            <td>{{$deal->pnl}}</td>
                                            <td>{{$deal->date_open}}</td>
                                            <td>{{$deal->date_close}}</td>
                                            <td><button type="button" class="btn btn-info expander">Expand</button></td>
                                        </tr>
                                        <tr style="display: none">
                                            <td colspan="8"><table class="table">
                                                    <p>Average price: {{$deal->average_price}}</p>
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
    <script type="module">
        $('.expander').click(function() {
            $(this).closest('tr').next().toggle();
        })
    </script>
@endsection
