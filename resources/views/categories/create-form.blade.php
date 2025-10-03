@extends('categories.main', ['title' => 'Create'])

@section('content')
<form action="{{ route('categories.create') }}" method="post">
    @csrf

    <div class="app-cmp-form-detail">
        <label for="app-inp-code">Code</label>
        <input type="text" id="app-inp-code" name="code" required />

        <label for="app-inp-name">Name</label>
        <input type="text" id="app-inp-name" name="name" required />

        <label for="app-inp-description">Description</label>
        <textarea id="app-inp-description" name="description" cols="80" rows="10" required></textarea>
    </div>

@php
  $cancelUrl = session('bookmarks.categories.create-form', route('categories.list'));
  if ($cancelUrl === url()->full()) { $cancelUrl = route('categories.list'); }
@endphp

   <div class="app-cmp-form-actions">
  <button type="submit">Create</button>
  <button type="button" onclick="window.location.href='{{ $cancelUrl }}'">Cancel</button>
</div>
</form>
@endsection

