@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{!$bot->id ? 'Create new' : 'Update'}} bot</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('bots.store') }}">
                            @csrf

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
                                <label for="api_key" class="col-md-4 col-form-label text-md-end">Api key</label>

                                <div class="col-md-6">
                                    <input id="api_key" type="text" class="form-control @error('api_key') is-invalid @enderror" name="api_key" value="" autocomplete="api_key">

                                    @error('api_key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="api_secret" class="col-md-4 col-form-label text-md-end">Api secret</label>

                                <div class="col-md-6">
                                    <input id="api_secret" type="text" class="form-control @error('api_secret') is-invalid @enderror" name="api_secret" value="" autocomplete="api_secret">

                                    @error('api_secret')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create
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
