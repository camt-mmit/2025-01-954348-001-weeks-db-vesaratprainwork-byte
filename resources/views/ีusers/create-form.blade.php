@extends('layouts.main', ['title' => 'Create User'])

@section('content')
  <form method="post" action="{{ route('users.create') }}" class="app-ly-max-width">
    @csrf
    <div class="app-cmp-form-detail">
      <label>Email</label>
      <input type="email" name="email" required />

      <label>Name</label>
      <input type="text" name="name" required />

      <label>Password</label>
      <input type="password" name="password" required placeholder="plain text; will be hashed" />

      <label>Role</label>
      <select name="role" required>
        <option value="USER">USER</option>
        <option value="ADMIN">ADMIN</option>
      </select>
    </div>
    <div class="app-cmp-form-actions">
      <button type="submit">Create</button>
      <a href="{{ route('users.list') }}"><button type="button">Cancel</button></a>
    </div>
  </form>
@endsection
