@extends('shops.main', [
    'title' => $shop->code,
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}" method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input id="app-criteria-term" type="text" name="term" value="{{ $criteria['term'] }}" placeholder="code / name / price" />
            </div>
            <div class="app-cmp-form-actions">
                <button type="submit" class="primary">Search</button>
                <a href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}"><button type="button" class="accent">X</button></a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            {{-- ฟอร์มกลางสำหรับ Add --}}
            <form action="{{ route('shops.add-products', ['shop' => $shop->code]) }}" id="app-form-add-product" method="post">
                @csrf
            </form>
            <ul class="app-cmp-links">
                <li><a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">&lt; Back</a></li>
                <li><a href="{{ route('products.create-form') }}">New Product</a></li>
            </ul>
        </nav>
        {{ $products->withQueryString()->links() }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>No. of Shops</th>
                <th>Add</th> 
            </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td class="app-cl-code">{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ optional($product->category)->code }}</td>
                <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                <td class="app-cl-number">{{ number_format($product->shops_count, 0) }}</td>
                <td>
                    <button type="submit" form="app-form-add-product" name="product" value="{{ $product->code }}" class="primary">
                        Add
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;">No products to add</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
