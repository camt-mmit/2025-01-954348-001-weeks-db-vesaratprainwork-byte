@extends('shops.main', [
    'title' => $shop->name,
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    @php
        session()->put('bookmarks.products.view', url()->full());          
        session()->put('bookmarks.shops.view-products', url()->full());    
        session()->put('bookmarks.categories.view', url()->full());        
    @endphp

    <search>
        <form action="{{ route('shops.view-products', ['shop' => $shop->code]) }}"
              method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term"
                       value="{{ $criteria['term'] ?? '' }}" placeholder="code / name" />

                <label for="app-criteria-min-price">Min Price</label>
                <input type="number" id="app-criteria-min-price" name="minPrice"
                       value="{{ $criteria['minPrice'] ?? '' }}" step="any" />

                <label for="app-criteria-max-price">Max Price</label>
                <input type="number" id="app-criteria-max-price" name="maxPrice"
                       value="{{ $criteria['maxPrice'] ?? '' }}" step="any" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="app-cl-link">Search</button>
                <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">
                    <button type="button" class="app-cl-link">Clear</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            <form action="{{ route('shops.remove-product', ['shop' => $shop->code]) }}"
                  id="app-form-remove-product" method="post">
                @csrf
            </form>

            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">&lt; Back</a>
                </li>

                @can('update', $shop)
                    <li>
                        <a href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}">Add Products</a>
                    </li>
                @endcan

                @can('create', \App\Models\Product::class)
                    <li>
                        <a href="{{ route('products.create-form') }}">New Product</a>
                    </li>
                @endcan
            </ul>
        </nav>
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
                <th>Price</th>
                <th>Category</th>
                <th>No. of Shops</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>
                        <a href="{{ route('products.view', ['product' => $product->code]) }}" class="app-cl-code">
                            {{ $product->code }}
                        </a>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->category)
                            <a href="{{ route('categories.view', ['category' => $product->category->code]) }}"
                               style="color: var(--app-primary-color);">
                                {{ $product->category->name }}
                            </a>
                        @else
                            <span class="app-cl-warn">—</span>
                        @endif
                    </td>
                    <td class="app-cl-number">{{ number_format($product->shops_count, 0) }}</td>
                    <td>
                        @can('update', $shop)
                            <button type="submit"
                                    form="app-form-remove-product"
                                    name="product" value="{{ $product->code }}"
                                    class="app-cl-link app-cl-warn">
                                Remove
                            </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

