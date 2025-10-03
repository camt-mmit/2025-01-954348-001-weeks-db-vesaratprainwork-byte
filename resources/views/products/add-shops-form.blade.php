@extends('products.main', [
    'mainClasses' => ['app-ly-max-width'],
    'title' => $product->code,
    'titleClasses' => ['app-cl-code'],
    'subTitle' => 'Add Shops',
])

@section('header')
    <search>
        <form action="{{ route('products.add-shops-form', [
            'product' => $product->code,
        ]) }}"
            method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="app-cl-primary">Search</button>
                <a
                    href="{{ route('products.add-shops-form', [
                        'product' => $product->code,
                    ]) }}">
                    <button type="button" class="app-cl-accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            <form action="{{ route('products.add-shop', [
                'product' => $product->code,
            ]) }}"
                id="app-form-add-shop" method="post">
                @csrf
            </form>

            <ul class="app-cmp-links">
                <li>
                    <a
                        href="{{ session()->get(
                            'products.add-shops-form',
                            route('products.view-shops', [
                                'product' => $product->code,
                            ]),
                        ) }}">&lt;
                        Back</a>
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
            <col />
            <col />
            <col style="width: 4ch;" />
            <col style="width: 0px;" />
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                <th>No. of Products</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @php
                session()->put('bookmarks.shops.view', url()->full());
            @endphp

            @foreach ($shops as $shop)
                <tr>
                    <td>
                        <a href="{{ route('shops.view', [
                            'shop' => $shop->code,
                        ]) }}"
                            class="app-cl-code">
                            {{ $shop->code }}
                        </a>
                    </td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    <td class="app-cl-number">{{ number_format($shop->products_count, 0) }}</td>
                    <td>
                        <button type="submit" form="app-form-add-shop" name="shop" value="{{ $shop->code }}">
                            Add
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection