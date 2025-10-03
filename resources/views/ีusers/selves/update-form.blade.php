@extends('layouts.main', ['title' => 'Update Your Profile'])

@section('content')
  @php session()->put('bookmarks.users.selves.update-form', url()->full()); @endphp

  <form method="post" action="{{ route('users.selves.update') }}" class="app-ly-max-width">
    @csrf
    <div class="app-cmp-form-detail">
      <label>Email</label>
      <input type="email" value="{{ $user->email }}" disabled />

      <label>Name</label>
      <input type="text" name="name" value="{{ $user->name }}" required />

      <label>Password</label>
      <input type="password" name="password" placeholder="Leave blank to keep old password" />

      <label>Role</label>
      <input type="text" value="{{ $user->role }}" disabled />
    </div>

    <div class="app-cmp-form-actions">
      <button type="submit">Update</button>
      <a href="{{ route('users.selves.view') }}"><button type="button">Cancel</button></a>
    </div>
  </form>
@endsection
