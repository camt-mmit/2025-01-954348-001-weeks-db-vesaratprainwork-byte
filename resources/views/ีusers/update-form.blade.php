@extends('layouts.main', ['title' => "Update: {$user->email}"])

@section('content')
  <form method="post" action="{{ route('users.update', ['user'=>$user->email]) }}" class="app-ly-max-width">
    @csrf
    <div class="app-cmp-form-detail">
      <label>Email</label>
      <input type="email" value="{{ $user->email }}" disabled />

      <label>Name</label>
      <input type="text" name="name" value="{{ $user->name }}" required />

      <label>Password</label>
      <input type="password" name="password" placeholder="Leave blank to keep old password" />

      <label>Role</label>
      @if(\Auth::id() === $user->id)
       
        <input type="text" value="{{ $user->role }}" disabled />
      @else
        <select name="role" required>
          <option value="USER" @selected($user->role==='USER')>USER</option>
          <option value="ADMIN" @selected($user->role==='ADMIN')>ADMIN</option>
        </select>
      @endif
    </div>

    <div class="app-cmp-form-actions">
      <button type="submit">Update</button>
      <a href="{{ route('users.view', ['user'=>$user->email]) }}"><button type="button">Cancel</button></a>
    </div>
  </form>
@endsection
