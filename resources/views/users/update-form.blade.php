@extends('layouts.main', [
    'title' => 'Users',
    'subTitle' => $user->email,
])

@section('content')
    @can('update', $user)
        @php
            $isSelf = auth()->id() === $user->id;
        @endphp

        <form action="{{ route('users.update', ['user' => $user->email]) }}"
              method="post"
              class="app-cmp-form-detail">
            @csrf

            <label for="app-inp-email">E-mail</label>
            <input type="email" id="app-inp-email" value="{{ $user->email }}" disabled />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $user->name }}" required />

            <label for="app-inp-password">Password</label>
            <input type="password"
                   id="app-inp-password"
                   name="password"
                   placeholder="Leave blank if you don’t need to update" />

            <label for="app-inp-role">Role</label>
            @if($isSelf)
                <div class="app-cl-label">
                    <strong>{{ $user->role }}</strong>
                    <span class="app-cl-warn" style="margin-left:1ch;">(Cannot change your own role)</span>
                </div>
                
                <input type="hidden" name="role" value="{{ $user->role }}">
            @else
                <select id="app-inp-role" name="role" required>
                    <option value="USER"  @selected($user->role === 'USER')>USER</option>
                    <option value="ADMIN" @selected($user->role === 'ADMIN')>ADMIN</option>
                </select>
            @endif

            <div class="app-cmp-form-actions">
                <button type="submit">Update</button>
                <a href="{{ route('users.view', ['user' => $user->email]) }}">
                    <button type="button">Cancel</button>
                </a>
            </div>
        </form>
    @else
        <div class="app-cmp-notifications">
            <div role="alert">You are not allowed to update this user.</div>
        </div>
    @endcan
@endsection
