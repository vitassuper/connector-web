@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex">
                        <h3>Exchanges</h3>
                        <div><a href="{{route('exchanges.create')}}" class="btn btn-success">Add exchange</a></div>
                    </div>
                    <div class="card-body">
                        @if($exchanges->count())
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Api key</th>
                                    <th>Date created</th>
                                    <th>Date modified</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($exchanges as $exchange)
                                    <tr>
                                        <td>{{$exchange->name}}</td>
                                        <td>{{$exchange->type}}</td>
                                        <td>********{{$exchange->getApiKeyShard()}}</td>
                                        <td>{{$exchange->created_at}}</td>
                                        <td>{{$exchange->updated_at}}</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="deleteButton btn btn-danger me-2" data-attr="{{route('exchanges.destroy', $exchange)}}" title="Delete">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        @else
                            <div class="col-md-8">
                                <span>There are no exchanges</span>&nbsp;<a href="{{route('exchanges.create')}}">Add exchange</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.delete')
@endsection
