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
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ $bot->name }}" required autocomplete="name" autofocus>

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
                                    <select id="exchange" class="form-select @error('exchange') is-invalid @enderror"
                                            name="exchange" required>
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

                            <div class="row mb-3">
                                <label for="side" class="col-md-4 col-form-label text-md-end">Side</label>

                                <div class="col-md-6">
                                    <select id="side" class="form-select @error('side') is-invalid @enderror"
                                            name="side" required>
                                        @foreach(\App\Models\Bot::getAvailableSides() as $side => $label)
                                            <option @if($bot->side === $side) selected @endif value="{{$side}}">{{$label}}</option>
                                        @endforeach
                                    </select>

                                    @error('side')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="secret" class="col-md-4 col-form-label text-md-end">Secret</label>

                                <div class="col-md-6">
                                    <input id="secret" type="text"
                                           class="form-control @error('secret') is-invalid @enderror" name="secret"
                                           value="{{ $bot->secret }}" required>
                                    @error('secret')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-info" id="generateSecret">Generate</button>
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
    <script type="module">
        $('#generateSecret').click(function () {
            $('input[name="secret"]').val(generateRandomString(32))
        });

        function generateRandomString(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }
    </script>
@endsection
