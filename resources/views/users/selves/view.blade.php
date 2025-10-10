@extends('layouts.main', [
    'title' => 'My Profile',
])

@section('header')
    <nav>
        <ul class="app-cmp-links">
            
            <li>
                <a href="{{ url()->previous() }}">&lt; Back</a>
            </li>

            
            @can('update', $user)
                <li><a href="{{ route('users.selves.update-form') }}">Update</a></li>
            @endcan
        </ul>
    </nav>
@endsection

@section('content')
    {{-- ส่วน content เหมือนเดิม --}}
    <dl class="app-cmp-data-detail">
        <dt>ID</dt>
        <dd class="app-cl-number">{{ $user->id }}</dd>

        <dt>Name</dt>
        <dd class="app-cl-name">{{ $user->name }}</dd>

        <dt>E-mail</dt>
        <dd>{{ $user->email }}</dd>

        <dt>Role</dt>
        <dd>{{ $user->role }}</dd>
    </dl>
@endsection