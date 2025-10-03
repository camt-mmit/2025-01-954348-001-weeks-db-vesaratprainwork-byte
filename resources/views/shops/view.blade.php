@extends('shops.main', [
    'title' => $shop->code,
    'titleClasses' => ['app-cl-code'],
])

@section('header')
    <nav>
        <form action="{{ route('shops.delete', ['shop' => $shop->code]) }}"
              method="post"
              id="app-form-delete">
            @csrf
        </form>

        <ul class="app-cmp-links">
            <li>
                <a href="{{ session()->get('bookmarks.shops.view', route('shops.list')) }}">&lt; Back</a>
            </li>

            <li>
                <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">View Products</a>
            </li>

            @can('addProduct', $shop)
                <li>
                    <a href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}">Add Products</a>
                </li>
            @endcan

            @can('update', $shop)
                <li>
                    <a href="{{ route('shops.update-form', ['shop' => $shop->code]) }}">Update</a>
                </li>
            @endcan

            @can('delete', $shop)
                <li class="app-cl-warn">
                    <button type="submit" form="app-form-delete" class="app-cl-link">Delete</button>
                </li>
            @endcan
        </ul>
    </nav>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd class="app-cl-code">{{ $shop->code }}</dd>

        <dt>Name</dt>
        <dd class="app-cl-name">{{ $shop->name }}</dd>

        <dt>Owner</dt>
        <dd>{{ $shop->owner }}</dd>

        <dt>Location</dt>
        <dd>
            <span class="app-cl-number">{{ $shop->latitude }}, {{ $shop->longitude }}</span>
        </dd>

        <dt>Address</dt>
        <dd>
            <pre style="margin: 0px;">{{ $shop->address }}</pre>
        </dd>
    </dl>
@endsection
