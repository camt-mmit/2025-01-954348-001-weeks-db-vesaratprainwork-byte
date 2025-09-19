@extends('categories.main', [
    'title' => 'Add Product to ' . $category->name,
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('categories.add-products-form', ['category' => $category->code]) }}"
              method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}"
                       placeholder="code / name" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="primary">Search</button>
                <a href="{{ route('categories.add-products-form', ['category' => $category->code]) }}">
                    <button type="button" class="accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            <form action="{{ route('categories.add-products', ['category' => $category->code]) }}"
                  id="app-form-add-product" method="post">
                @csrf
            </form>
            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('categories.view-products', ['category' => $category->code]) }}">
                        &lt; Back
                    </a>
                </li>
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
            <th>No. of Shops</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @if($product->category)
                        <a href="{{ route('categories.view', ['category' => $product->category->code]) }}">
                            {{ $product->category->name }}
                        </a>
                    @endif
                </td>
                <td class="app-cl-number">{{ $product->shops_count }}</td>
                <td>
                    <button type="submit"
                            form="app-form-add-product"
                            name="product" value="{{ $product->code }}">
                        Add
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
