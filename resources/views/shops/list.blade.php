@extends('shops.main', [
'title' => 'List',
'mainClasses' => ['app-ly-max-width'],
])

@section('header')
<search>
    <form action="{{ route('shops.list') }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail">
            <label for="app-criteria-term">Search</label>
            <input
                type="text"
                id="app-criteria-term"
                name="term"
                value="{{ $criteria['term'] ?? '' }}"
                placeholder="code / name / owner" />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('shops.list') }}">
                <button type="button" class="accent">X</button>
            </a>
        </div>
    </form>
</search>

<div class="app-cmp-links-bar">
    <nav>
        @php
            session()->put('bookmarks.shops.view', url()->full());
            session()->put('bookmarks.shops.create-form', url()->full());
        @endphp
        <ul class="app-cmp-links">
            @can('create', \App\Models\Shop::class)
                <li><a href="{{ route('shops.create-form') }}">New Shop</a></li>
            @endcan
        </ul>
    </nav>

    {{ $shops->withQueryString()->links() }}
</div>
@endsection

@section('content')
<table class="app-cmp-data-list">
    <colgroup>
        <col style="width: 5ch;" />
        <col />      {{-- Name --}}
        <col />      {{-- Owner --}}
        <col style="width: 1%;" /> {{-- Actions --}}
    </colgroup>

    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Owner</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($shops as $shop)
        <tr>
            <td>
                <a href="{{ route('shops.view', ['shop' => $shop->code]) }}" class="app-cl-code">
                    {{ $shop->code }}
                </a>
            </td>
            <td>{{ $shop->name }}</td>
            <td>{{ $shop->owner }}</td>
            <td>
                @can('update', $shop)
                    <a href="{{ route('shops.update-form', ['shop' => $shop->code]) }}">Update</a>
                @endcan

                @can('delete', $shop)
                    <form action="{{ route('shops.delete', ['shop' => $shop->code]) }}"
                          method="post" style="display:inline;">
                        @csrf
                        <button type="submit" class="app-cl-link app-cl-warn">Delete</button>
                    </form>
                @endcan
            </td>
        </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;">No shops</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
