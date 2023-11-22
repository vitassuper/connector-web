@extends('layouts.app')
@section('content')
    <div class="container">

        <h1>Liquidations</h1>
        <form action="/" class="mb-2">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-2 d-flex">
                    <select name="symbol" id="symbol" class="form-select">
                        @foreach($symbols as $symbol)
                            <option @if($selected_symbol === $symbol->symbol) selected
                                    @endif value="{{ $symbol->symbol }}">{{ $symbol->symbol }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-success my-2" type="submit">Filter</button>
                </div>

            </div>
        </form>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    Symbol
                </th>
                <th>
                    Side
                </th>
                <th>
                    Quantity
                </th>
                <th>
                    Datetime
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($liquidations as $liquidation)
                <tr>
                    <td>{{$liquidation->symbol}}</td>
                    <td>{{$liquidation->side}}</td>
                    <td>{{$liquidation->total_volume}}</td>
                    <td>{{$liquidation->created_at->toDateTimeString()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
