@extends('layouts.main', [
    'title' => 'Users',
])

@section('header')
    <nav class="app-cmp-links-bar">
        <div>
            @can('create', \App\Models\User::class)
                <ul class="app-cmp-links">
                    <li><a href="{{ route('users.create-form') }}">New User</a></li>
                </ul>
            @endcan
        </div>
    </nav>

    <search>
        <form action="{{ route('users.list') }}" method="get" class="app-cmp-search-form">
            <div class="app-cmp-form-detail">
                <label for="app-criteria-term">Search</label>
                <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] ?? '' }}" />
            </div>
            <div class="app-cmp-form-actions">
                <button type="submit">Search</button>
            </div>
        </form>
    </search>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <thead>
        <tr>
            <th style="width:1%;">ID</th>
            <th>Name</th>
            <th style="width:25%;">E-mail</th>
            <th style="width:1%;">Role</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $u)
            <tr>
                <td class="app-cl-number">{{ $u->id }}</td>
                <td>
                    <a href="{{ route('users.view', ['user' => $u->email]) }}" class="app-cl-name">
                        {{ $u->name }}
                    </a>
                </td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;">No users</td></tr>
        @endforelse
        </tbody>
    </table>

    @if(method_exists($users, 'links'))
        <div style="margin-top:8px;">{{ $users->links() }}</div>
    @endif
@endsection
