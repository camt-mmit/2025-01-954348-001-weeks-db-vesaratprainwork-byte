@extends('layouts.main', ['title' => 'Your Profile'])

@section('header')
  <nav>
    <ul class="app-cmp-links">
      <li><a href="{{ route('users.selves.update-form') }}">Update</a></li>
      <li>
        <a href="{{ session()->get('bookmarks.users.selves.view', route('products.list')) }}">&lt; Back</a>
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
