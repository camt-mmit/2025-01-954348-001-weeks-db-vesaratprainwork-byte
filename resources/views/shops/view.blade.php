@extends('shops.main', [
    'title' => $shop->name,
])

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd><span class="app-cl-code">{{ $shop->code }}</span></dd>

        <dt>Name</dt>
        <dd>{{ $shop->name }}</dd>

        <dt>Owner</dt>
        <dd>{{ $shop->owner }}</dd>

        <dt>Latitude</dt>
        <dd class="app-cl-number">{{ $shop->latitude }}</dd>

        <dt>Longitude</dt>
        <dd class="app-cl-number">{{ $shop->longitude }}</dd>

        <dt>Address</dt>
        <dd><div class="app-cl-multiline">{{ $shop->address }}</div></dd>
    </dl>
@endsection
