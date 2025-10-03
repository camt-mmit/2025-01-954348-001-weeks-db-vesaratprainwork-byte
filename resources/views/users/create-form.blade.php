@extends('layouts.main', [
    'title' => 'Users',
    'subTitle' => 'Create',
])

@section('content')
    @can('create', \App\Models\User::class)
        <form action="{{ route('users.create') }}" method="post" class="app-cmp-form-detail">
            @csrf

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" required />

            <label for="app-inp-email">E-mail</label>
            <input type="email" id="app-inp-email" name="email" required />

            <label for="app-inp-password">Password</label>
            <input type="password" id="app-inp-password" name="password" required />

            <label for="app-inp-role">Role</label>
            <select id="app-inp-role" name="role" required>
                <option value="USER">USER</option>
                <option value="ADMIN">ADMIN</option>
            </select>

            <div class="app-cmp-form-actions">
                <button type="submit">Create</button>
                <a href="{{ route('users.list') }}"><button type="button">Cancel</button></a>
            </div>
        </form>
    @else
        <div class="app-cmp-notifications"><div role="alert">You are not allowed to create users.</div></div>
    @endcan
@endsection
