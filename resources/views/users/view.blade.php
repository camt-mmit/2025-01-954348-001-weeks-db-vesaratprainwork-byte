@extends('layouts.main', [
    'title' => 'User',
    'subTitle' => $user->email,
])

@section('header')
    <nav>
        <form action="{{ route('users.delete', ['user' => $user->email]) }}" method="post" id="app-form-delete">
            @csrf
        </form>

        <ul class="app-cmp-links">
            <li>
                <a href="{{ session()->get('bookmarks.users.view', route('users.list')) }}">&lt; Back</a>
            </li>


            @can('update', $user)
                <li><a href="{{ route('users.update-form', ['user' => $user->email]) }}">Update</a></li>
            @endcan

            @can('delete', $user)
                <li class="app-cl-warn">
                    <button type="submit" form="app-form-delete" class="app-cl-link">Delete</button>
                </li>
            @endcan
        </ul>
    </nav>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Email</dt>
        <dd class="app-cl-code">{{ $user->email }}</dd>

        <dt>Name</dt>
        <dd class="app-cl-name">{{ $user->name }}</dd>

        <dt>Role</dt>
        <dd>{{ $user->role }}</dd>
    </dl>
@endsection
