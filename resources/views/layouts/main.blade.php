<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />

    <title>{{ $title }}</title>
</head>

<body>
    <header id="app-cmp-main-header">
        <nav>
            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('products.list') }}">Products</a>
                </li>
                <li>
                    <a href="{{ route('shops.list') }}">Shops</a>
                </li>
                <li>
                    <a href="{{ route('categories.list') }}">Categories</a>
                </li>


                @can('create', \App\Models\User::class)
                <li><a href="{{ route('users.list') }}">Users</a></li>
                @endcan

                @auth
                @php
                if (!Route::is('users.selves.*')) {
                session()->put('bookmarks.users.selves.view', url()->full());
                }
                @endphp
                <li>
                    @if (Route::has('users.selves.view'))
                    <a href="{{ route('users.selves.view') }}">{{ \Auth::user()->name }}</a>
                    @else
                    <span>{{ \Auth::user()->name }}</span>
                    @endif
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="post" style="display: inline;">
                        @csrf
                        <button type="submit" class="app-cl-link">Logout</button>
                    </form>
                </li>
                @endauth
                {{-- DEBUG (ชั่วคราว): ดู role และผล can --}}
{{-- Role: {{ \Auth::user()->role ?? 'guest' }} --}}
{{-- @can('create', \App\Models\User::class) CAN(create User) = YES @else NO @endcan --}}


            </ul>
        </nav>
    </header>

    <main id="app-cmp-main-content" @class($mainClasses ?? [])>
        <header>
            <h1><span @class($titleClesses ?? [])>{{ $title }}</span></h1>

            {{-- Notifications --}}
            <div class="app-cmp-notifications">
                @session('status')
                <div role="status">
                    {{ $value }}
                </div>
                @endsession
            </div>

            @yield('header')
        </header>

        @yield('content')
    </main>

    <footer id="app-cmp-main-footer">
        &#xA9; Copyright Vesarat Database.
    </footer>
</body>

</html>