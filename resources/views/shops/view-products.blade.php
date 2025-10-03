@extends('shops.main', [
    'mainClasses' => ['app-ly-max-width'],
    'title' => $shop->code,
    'titleClasses' => ['app-cl-code'],
    'subTitle' => 'Products',
])

@section('header')
    <search>
        <form action="{{ route('shops.view-products', [
            'shop' => $shop->code,
        ]) }}" method="get"
            class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}" />

                <label for="app-criteria-min-price">Min Price</label>
                <input type="number" id="app-criteria-min-price" name="minPrice" value="{{ $criteria['minPrice'] }}"
                    step="any" />

                <label for="app-criteria-max-price">Max Price</label>
                <input type="number" id="app-criteria-max-price" name="maxPrice" value="{{ $criteria['maxPrice'] }}"
                    step="any" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="app-cl-primary">Search</button>
                <a
                    href="{{ route('shops.view-products', [
                        'shop' => $shop->code,
                    ]) }}">
                    <button type="button" class="app-cl-accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            <form action="{{ route('shops.remove-product', [
                'shop' => $shop->code,
            ]) }}"
                id="app-form-remove-product" method="post">
                @csrf
            </form>

            <ul class="app-cmp-links">
                @php
                    session()->put('bookmarks.shops.add-products-form', url()->full());
                @endphp

                <li>
                    <a
                        href="{{ session()->get(
                            'bookmarks.shops.view-products',
                            route('shops.view', [
                                'shop' => $shop->code,
                            ]),
                        ) }}">&lt;
                        Back</a>
                </li>
                <li>
                    <a
                        href="{{ route('shops.add-products-form', [
                            'shop' => $shop->code,
                        ]) }}">Add
                        Products</a>
                </li>
            </ul>
        </nav>

        {{ $products->withQueryString()->links() }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <colgroup>
            <col style="width: 5ch;" />
            <col />
            <col />
            <col />
            <col style="width: 4ch;" />
            <col style="width: 0px;" />
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>No. of Shops</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @php
                session()->put('bookmarks.products.view', url()->full());
                session()->put('bookmarks.categories.view', url()->full());
            @endphp

            @foreach ($products as $product)
                <tr>
                    <td>
                        <a href="{{ route('products.view', [
                            'product' => $product->code,
                        ]) }}"
                            class="app-cl-code">
                            {{ $product->code }}
                        </a>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>
                        <a href="{{ route('categories.view', [
                            'category' => $product->category->code,
                        ]) }}"
                            class="app-cl-name">{{ $product->category->name }}</a>
                    </td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td class="app-cl-number">{{ number_format($product->shops_count, 0) }}</td>
                    <td>
                        <button type="submit" form="app-form-remove-product" name="product" value="{{ $product->code }}">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection