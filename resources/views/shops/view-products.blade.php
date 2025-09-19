@extends('shops.main', [
    'title' => $shop->name,
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('shops.view-products', ['shop' => $shop->code]) }}" method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input
                    type="text"
                    id="app-criteria-term"
                    name="term"
                    value="{{ $criteria['term'] }}"
                    placeholder="code / name" />

                <label for="app-criteria-min-price">Min Price</label>
                <input type="number" id="app-criteria-min-price" name="minPrice" value="{{ $criteria['minPrice'] }}" step="any" />

                <label for="app-criteria-max-price">Max Price</label>
                <input type="number" id="app-criteria-max-price" name="maxPrice" value="{{ $criteria['maxPrice'] }}" step="any" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="primary">Search</button>
                <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">
                    <button type="button" class="accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            {{-- ฟอร์มกลางสำหรับ REMOVE (ปุ่มแต่ละแถวจะ submit มาที่ฟอร์มนี้) --}}
            <form action="{{ route('shops.remove-product', ['shop' => $shop->code]) }}"
                  id="app-form-remove-product" method="post">
                @csrf
            </form>

            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                        &lt; Back
                    </a>
                </li>
                <li>
                    <a href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}">
                        Add Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.create-form') }}">New Product</a>
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
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>No. of Shops</th>
                <th></th> {{-- คอลัมน์ปุ่ม Remove --}}
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
                            <a href="{{ route('categories.view', ['category' => $product->category->code]) }}">
                                {{ $product->category->name }}
                            </a>
                        @endif
                    </td>
                    <td class="app-cl-number">{{ number_format($product->shops_count, 0) }}</td>
                    <td>
                        <button type="submit"
                                form="app-form-remove-product"
                                name="product" value="{{ $product->code }}"
                                class="app-cl-link app-cl-warn">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
