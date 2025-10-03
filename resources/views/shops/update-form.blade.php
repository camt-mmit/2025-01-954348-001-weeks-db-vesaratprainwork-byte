@extends('shops.main', [
    'title' => $shop->code,
    'titleClasses' => ['app-cl-code'],
])

@section('content')
    <form action="{{ route('shops.update', [
        'shop' => $shop->code,
    ]) }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" value="{{ $shop->code }}" required class="app-cl-code" />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $shop->name }}" required />

            <label for="app-inp-owner">Owner</label>
            <input type="text" id="app-inp-owner" name="owner" value="{{ $shop->owner }}" required />

            <label for="app-inp-latitude">Latitude</label>
            <input type="number" id="app-inp-latitude" name="latitude" value="{{ $shop->latitude }}" step="any"
                required />

            <label for="app-inp-longitude">Longitude</label>
            <input type="number" id="app-inp-longitude" name="longitude" value="{{ $shop->longitude }}" step="any"
                required />

            <label for="app-inp-address">Address</label>
            <textarea id="app-inp-address" name="address" cols="80" rows="10" required>{{ $shop->address }}</textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Update</button>
            <a
                href="{{ session()->get(
                    'bookmarks.shops.update-form',
                    route('shops.view', [
                        'shop' => $shop->code,
                    ]),
                ) }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
@endsection