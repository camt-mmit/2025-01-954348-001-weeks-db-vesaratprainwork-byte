@extends('products.main', [
'title' => 'List',
'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('products.list') }}" method="get" class="app-cmp-search-form">
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
                <a href="{{ route('products.list') }}">
                    <button type="button" class="app-cl-accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            @php
                session()->put('bookmarks.products.create-form', url()->full());
            @endphp

            <ul class="app-cmp-links">
                @can('create', \App\Models\Product::class)
                    <li>
                        <a href="{{ route('products.create-form') }}">New Product</a>
                    </li>
                @endcan
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
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>No. of Shops</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
<search>
    <form action="{{ route('products.list') }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail">
            <label for="app-criteria-term">Search</label>
            <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] ?? '' }}" />

            <label for="app-criteria-min-price">Min Price</label>
            <input type="number" id="app-criteria-min-price" name="minPrice" value="{{ $criteria['minPrice'] ?? '' }}" step="any" />

            <label for="app-criteria-max-price">Max Price</label>
            <input type="number" id="app-criteria-max-price" name="maxPrice" value="{{ $criteria['maxPrice'] ?? '' }}" step="any" />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('products.list') }}">
                <button type="button" class="accent">X</button>
            </a>
        </div>
    </form>
</search>

<div class="app-cmp-links-bar">
    <nav>
        @php
            session()->put('bookmarks.products.view', url()->full());
            session()->put('bookmarks.categories.view', url()->full());
        @endphp

        <ul class="app-cmp-links">
            @can('create', \App\Models\Product::class)
                <li><a href="{{ route('products.create-form') }}">New Product</a></li>
            @endcan
        </ul>
    </nav>

    {{ $products->withQueryString()->links() }}
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
            <th style="width:1%;">Actions</th>
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
                    <a
                        href="{{ route('categories.view', ['category' => $product->category->code]) }}"
                        style="color: var(--app-primary-color);">
                        {{ $product->category->name }}
                    </a>
                @else
                    <span class="app-cl-warn">—</span>
                @endif
            </td>

            <td class="app-cl-number">{{ $product->shops_count }}</td>

            <td>
                @can('update', $product)
                    <a href="{{ route('products.update-form', ['product' => $product->code]) }}">Update</a>
                @endcan

                @can('delete', $product)
                    <form action="{{ route('products.delete', ['product' => $product->code]) }}"
                          method="post" style="display:inline;">
                        @csrf
                        <button type="submit" class="app-cl-link app-cl-warn">Delete</button>
                    </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

