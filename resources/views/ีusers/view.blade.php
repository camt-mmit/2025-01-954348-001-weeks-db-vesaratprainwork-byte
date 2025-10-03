@extends('layouts.main', ['title' => $user->email])

@section('header')
  @php session()->put('bookmarks.users.view', url()->full()); @endphp
  <nav>
    <ul class="app-cmp-links">
      @can('create', \App\Models\User::class)
        <li><a href="{{ route('users.update-form', ['user'=>$user->email]) }}">Update</a></li>
        @if(\Auth::id() !== $user->id)
          <li class="app-cl-warn">
            <form id="app-form-delete" action="{{ route('users.delete', ['user'=>$user->email]) }}" method="post">
              @csrf
              <button type="submit" class="app-cl-link">Delete</button>
            </form>
          </li>
        @endif
      @endcan
      <li>
        <a href="{{ session()->get('bookmarks.users.view', route('users.list')) }}">&lt; Back</a>
      </li>
    </ul>
  </nav>
@endsection

@section('content')
  <dl class="app-cmp-data-detail app-ly-max-width">
    <dt>Email</dt><dd class="app-cl-code">{{ $user->email }}</dd>
    <dt>Name</dt><dd>{{ $user->name }}</dd>
    <dt>Role</dt><dd>{{ $user->role }}</dd>
  </dl>
@endsection
