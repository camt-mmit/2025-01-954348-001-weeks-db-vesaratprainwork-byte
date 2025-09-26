@extends('shops.main', [
    'title' => $shop->name,
])

@section('header')
    <nav>
        <form action="{{ route('shops.delete', ['shop' => $shop->code]) }}" method="post" id="app-form-delete">
            @csrf
        </form>

        <ul class="app-cmp-links">
            <li>
                <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">View Products</a>  {{-- ✅ --}}
            </li>
            <li>
                <a href="{{ route('shops.update-form', ['shop' => $shop->code]) }}">Update</a>
            </li>
            <li class="app-cl-warn">
                <button type="submit" form="app-form-delete" class="app-cl-link">Delete</button>
            </li>
        </ul>
    </nav>
@endsection


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

        <dd><span class="app-cl-number">{{ $shop->latitude }}, {{ $shop->longitude }}</span></dd>

        <dt>Address</dt>
        <dd><pre style="margin: 0px;">{{ $shop->address }}</pre></dd>

<dd class="app-cl-number -left">
    {{ $shop->latitude }}, {{ $shop->longitude }}
</dd>
        <dt>Address</dt>
        <dd>
            <div class="app-cl-multiline">{{ $shop->address }}</div>
        </dd>

    </dl>
@endsection

