@extends('products.main', [
    'title' => $product->code,
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('products.view-shops', ['product' => $product->code]) }}"
              method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term"
                       value="{{ $criteria['term'] }}" placeholder="code / name / owner" />
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
            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('products.view', ['product' => $product->code]) }}">
                        &lt; Back
                    </a>
                </li>
                <li>
                   
                    <a href="{{ route('products.add-shops-form', ['product' => $product->code]) }}">
                        Add Shops
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
                <th>Remove</th> 
            </tr>
        </thead>

        <tbody>
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
                        <form action="{{ route('products.remove-shop', ['product' => $product->code, 'shop' => $shop->code]) }}" method="post">
                            @csrf
                            <button type="submit" class="app-cl-link app-cl-warn">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

