@extends('shops.main', [
    'title' => 'Create',
])

@section('content')
    <form action="{{ route('shops.create') }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" required class="app-cl-code" />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" required />

            <label for="app-inp-owner">Owner</label>
            <input type="text" id="app-inp-owner" name="owner" required />

            <label for="app-inp-latitude">Latitude</label>
            <input type="number" id="app-inp-latitude" name="latitude" step="any" required />

            <label for="app-inp-longitude">Longitude</label>
            <input type="number" id="app-inp-longitude" name="longitude" step="any" required />

            <label for="app-inp-address">Address</label>
            <textarea id="app-inp-address" name="address" cols="80" rows="10" required></textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Create</button>
            <a href="{{ session()->get('bookmarks.shops.create-form', route('shops.list')) }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
@endsection