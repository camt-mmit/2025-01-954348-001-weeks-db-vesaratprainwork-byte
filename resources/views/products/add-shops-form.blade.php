@extends('products.main', [
'title' => $product->code,
'mainClasses' => ['app-ly-max-width'],
])

@section('header')
<search>
    <form action="{{ route('products.add-shops-form', ['product' => $product->code]) }}"
        method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail">
            <label for="app-criteria-term">Search</label>
            <input
                type="text"
                id="app-criteria-term"
                name="term"
                value="{{ $criteria['term'] }}"
                placeholder="code / name / owner" />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('products.view-shops', ['product' => $product->code]) }}">
                <button type="button" class="accent">X</button>
            </a>
        </div>
    </form>
</search>

<div class="app-cmp-links-bar">
    <nav>

        @php
        session()->put('bookmarks.products.add-shops-form', url()->full());
        @endphp

        <form action="{{ route('products.add-shops-form', [
 'product' => $product->code,
 ]) }}" id="app-form-add-shop" method="post">
            @csrf
        </form>
        <ul class="app-cmp-links">
            <li>
                <a href="{{ session()->get('bookmarks.products.view-shops', route('products.view-shops', ['product' => $product->code])) }}">
                    &lt; Back
                </a>
            </li>

            <li>
                <a href="{{ route('shops.create-form') }}">New Shop</a>
            </li>
        </ul>
    </nav>

    {{ $shops->withQueryString()->links() }}
</div>
@endsection

@section('content')
<table class="app-cmp-data-list">
    <colgroup>
        <col style="width: 5ch;" />
    </colgroup>

    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Owner</th>
            <th>No. of Products</th>
            <th>Add</th>
        </tr>
    </thead>

    <tbody>

            @php
        session()->put('bookmarks.shops.view', url()->full());
        @endphp



        @foreach ($shops as $shop)
        <tr>
            <td>
                <a href="{{ route('shops.view', ['shop' => $shop->code]) }}" class="app-cl-code">
                    {{ $shop->code }}
                </a>
            </td>
            <td>{{ $shop->name }}</td>
            <td>{{ $shop->owner }}</td>
            <td class="app-cl-number">{{ number_format($shop->products_count, 0) }}</td>
            <td>
                @can('update', $product)
                <button type="submit" form="app-form-add-shop" name="shop"
                    value="{{ $shop->code }}">Add</button>
                    @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection