@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{!$bot->id ? 'Create new' : 'Update'}} bot</div>

                    <div class="card-body">
                        <form method="POST" action="{{ !$bot->id ? route('bots.store') : route('bots.update', $bot) }}">
                            @csrf

                            @if($bot->id)
                                @method('PUT')
                            @endif

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $bot->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="exchange" class="col-md-4 col-form-label text-md-end">Exchange</label>

                                <div class="col-md-6">
                                    <select id="exchange" class="form-select @error('exchange') is-invalid @enderror" name="exchange" required>
                                        @foreach($exchanges as $exchange)
                                            <option value="{{$exchange->id}}">{{$exchange->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('exchange')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{!$bot->id ? 'Create' : 'Update'}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
