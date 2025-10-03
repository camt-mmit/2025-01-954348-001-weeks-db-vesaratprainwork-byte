@extends('layouts.main', [
    'title' => 'My Profile',
    'subTitle' => 'Update',
])

@section('content')
    @can('update', $user)
        <form action="{{ route('users.selves.update') }}" method="post" class="app-cmp-form-detail">
            @csrf

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $user->name }}" required />

            <label for="app-inp-email">E-mail</label>
            <input type="email" id="app-inp-email" name="email" value="{{ $user->email }}" required />

            <label for="app-inp-password">Password</label>
            <input type="password" id="app-inp-password" name="password" placeholder="(leave blank to keep)" />

            <div class="app-cmp-form-actions">
                <button type="submit">Update</button>
                <a href="{{ route('users.selves.view') }}"><button type="button">Cancel</button></a>
            </div>
        </form>
    @else
        <div class="app-cmp-notifications"><div role="alert">You are not allowed to update this profile.</div></div>
    @endcan
@endsection
