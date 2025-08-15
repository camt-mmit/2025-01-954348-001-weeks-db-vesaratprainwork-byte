@extends('shops.main', [
    'title' => $shop->name,
])

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd>
            <a class="app-cl-code" href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                {{ $shop->code }}
            </a>
        </dd>

        <dt>Name</dt>
        <dd>{{ $shop->name }}</dd>

        <dt>Owner</dt>
        <dd>{{ $shop->owner }}</dd>

        <dt>Location</dt>
<dd class="app-cl-number -left">
    {{ $shop->latitude }}, {{ $shop->longitude }}
</dd>


        <dt>Address</dt>
        <dd>
            <div class="app-cl-multiline">{{ $shop->address }}</div>
        </dd>
    </dl>
@endsection
