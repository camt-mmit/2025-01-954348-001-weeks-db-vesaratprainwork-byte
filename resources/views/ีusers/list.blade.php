@extends('layouts.main', ['title' => 'Users'])

@section('header')
<div class="app-cmp-links-bar">
  <div>
    @can('create', \App\Models\User::class)
      <ul class="app-cmp-links">
        <li><a href="{{ route('users.create-form') }}">+ Create User</a></li>
      </ul>
    @endcan
  </div>
</div>
@endsection

@section('content')
  <form class="app-cmp-search-form" method="get" action="{{ route('users.list') }}">
    <div class="app-cmp-form-detail app-ly-max-width">
      <label>Term</label>
      <input type="text" name="term" value="{{ $criteria['term'] ?? '' }}" />
    </div>
    <div class="app-cmp-form-actions">
      <button type="submit">Search</button>
    </div>
  </form>

  <table class="app-cmp-data-list">
    <thead>
      <tr>
        <th>Email</th>
        <th>Name</th>
        <th>Role</th>
        <th style="width:1%;">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
        <tr>
          <td><a class="app-cl-code" href="{{ route('users.view', ['user'=>$u->email]) }}">{{ $u->email }}</a></td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->role }}</td>
          <td>
            @can('create', \App\Models\User::class)
              <a href="{{ route('users.update-form', ['user'=>$u->email]) }}">Update</a>
            @endcan
          </td>
        </tr>
      @empty
        <tr><td colspan="4" style="text-align:center;">No users</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $users->withQueryString()->links() }}
@endsection
