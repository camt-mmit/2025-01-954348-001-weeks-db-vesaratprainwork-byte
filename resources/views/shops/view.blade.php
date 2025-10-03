@extends('shops.main', [
    'title' => $shop->name,
])

@section('header')
    @php
        $backUrl = session('bookmarks.shops.view', route('shops.list'));
        if ($backUrl === url()->full()) {
            $backUrl = route('shops.list');
        }
    @endphp

    <nav>
        
        @can('delete', $shop)
            <form action="{{ route('shops.delete', ['shop' => $shop->code]) }}" method="post" id="app-form-delete">
                @csrf
            </form>
        @endcan

        <ul class="app-cmp-links">
            <li><a href="{{ $backUrl }}">&lt; Back</a></li>

           
            <li>
                <a href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">View Products</a>
            </li>

            
            @can('update', $shop)
                <li>
                    <a href="{{ route('shops.update-form', ['shop' => $shop->code]) }}">Update</a>
                </li>
            @endcan

           
            @can('delete', $shop)
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
            <a class="app-cl-code" href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                {{ $shop->code }}
            </a>
        </dd>

        <dt>Name</dt>
        <dd>{{ $shop->name }}</dd>

        <dt>Owner</dt>
        <dd>{{ $shop->owner }}</dd>

        <dt>Location</dt>
        <dd class="app-cl-number -left">{{ $shop->latitude }}, {{ $shop->longitude }}</dd>

        <dt>Address</dt>
        <dd><pre style="margin:0">{{ $shop->address }}</pre></dd>
    </dl>
@endsection



