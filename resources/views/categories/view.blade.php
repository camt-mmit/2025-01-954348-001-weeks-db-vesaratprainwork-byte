@extends('categories.main', [
    'title' => $category->code,
    'titleClasses' => ['app-cl-code'],
])

@section('header')
    <nav>
        <form action="{{ route('categories.delete', [
            'category' => $category->code,
        ]) }}" method="post"
            id="app-form-delete">
            @csrf
        </form>

        <ul class="app-cmp-links">
            @php
                session()->put('bookmarks.categories.view-products', url()->full());
            @endphp

            <li>
                <a href="{{ session()->get('bookmarks.categories.view', route('categories.list')) }}">&lt; Back</a>
            </li>
            <li>
                <a
                    href="{{ route('categories.view-products', [
                        'category' => $category->code,
                    ]) }}">View
                    Products</a>
            </li>
            <li>
                <a
                    href="{{ route('categories.update-form', [
                        'category' => $category->code,
                    ]) }}">Update</a>
            </li>
            @can('delete', $category)
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
            <span class="app-cl-code">{{ $category->code }}</span>
        </dd>

        <dt>Name</dt>
        <dd>
            {{ $category->name }}
        </dd>
    </dl>

    <pre>{{ $category->description }}</pre>
@endsection
