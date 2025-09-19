@extends('shops.main', [
    'title' => $shop->code,
])

@section('content')
    <form action="{{ route('shops.update', ['shop' => $shop->code,]) }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" value="{{ $shop->code }}" required />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $shop->name }}" required />

            <label for="app-inp-owner">Owner</label>
            <input type="text" id="app-inp-owner" name="owner" value="{{ $shop->owner }}" required />

            <label for="app-inp-lat">Latitude</label>
            <input type="number" id="app-inp-lat" name="latitude" value="{{ $shop->latitude }}" step="any" required />

            <label for="app-inp-lng">Longitude</label>
            <input type="number" id="app-inp-lng" name="longitude" value="{{ $shop->longitude }}" step="any" required />

            <label for="app-inp-address">Address</label>
            <textarea id="app-inp-address" name="address" cols="80" rows="8" required>{{ $shop->address }}</textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Update</button>
        </div>
    </form>
@endsection

