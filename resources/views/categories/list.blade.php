@extends('categories.main', [
    'title' => 'List',
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    <search>
        <form action="{{ route('categories.list') }}" method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}" />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit" class="app-cl-primary">Search</button>
                <a href="{{ route('categories.list') }}">
                    <button type="button" class="app-cl-accent">X</button>
                </a>
            </div>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav>
            @php
                session()->put('bookmarks.categories.create-form', url()->full());
            @endphp

            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('categories.create-form') }}">New Category</a>
                </li>
            </ul>
        </nav>

        {{ $categories->withQueryString()->links() }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <colgroup>
            <col style="width: 5ch;" />
            <col />
            <col style="width: 4ch;" />
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>No. of Products</th>
            </tr>
        </thead>

        <tbody>
            @php
                session()->put('bookmarks.categories.view', url()->full());
            @endphp

            @foreach ($categories as $category)
                <tr>
                    <td>
                        <a href="{{ route('categories.view', [
                            'category' => $category->code,
                        ]) }}"
                            class="app-cl-code">
                            {{ $category->code }}
                        </a>
                    </td>
                    <td>{{ $category->name }}</td>
                    <td class="app-cl-number">{{ $category->products_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection