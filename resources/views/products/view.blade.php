@extends('products.main', [
    'title' => $product->code,
    'titleClasses' => ['app-cl-code'],
])

@section('header')
    <nav>
        <form action="{{ route('products.delete', [
            'product' => $product->code,
        ]) }}" method="post"
            id="app-form-delete">
            @csrf
        </form>

        <ul class="app-cmp-links">
            @php
                session()->put('bookmarks.products.view-shops', url()->full());
            @endphp

            <li>
                <a href="{{ session()->get('bookmarks.products.view', route('products.list')) }}">&lt; Back</a>
            </li>
            <li>
                <a
                    href="{{ route('products.view-shops', [
                        'product' => $product->code,
                    ]) }}">View
                    Shops</a>
            </li>
            @can('update', $product)
                <li>
                    <a
                        href="{{ route('products.update-form', [
                            'product' => $product->code,
                        ]) }}">Update</a>
                </li>
            @endcan
            @can('delete', $product)
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
        <dd>
            <span class="app-cl-code">{{ $product->code }}</span>
        </dd>

        <dt>Name</dt>
        <dd>
            {{ $product->name }}
        </dd>

        <dt>Category</dt>
        <dd>
            [<span class="app-cl-code">{{ $product->category->code }}</span>]
            <span>{{ $product->category->name }}</span>
        </dd>

        <dt>Price</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $product->price }}</span>
        </dd>
    </dl>

    <pre>{{ $product->description }}</pre>
@endsection