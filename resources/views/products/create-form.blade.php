@extends('products.main', [
    'title' => 'Create',
])

@section('content')
    <form action="{{ route('products.create') }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" required class="app-cl-code" />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" required />

            <label for="app-inp-category">Category</label>
            <select id="app-inp-category" name="category" required>
                <option value="">--- Please Select Category ---</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->code }}">[{{ $category->code }}] {{ $category->name }}</option>
                @endforeach
            </select>

            <label for="app-inp-price">Price</label>
            <input type="number" id="app-inp-price" name="price" step="any" required />

            <label for="app-inp-description">Description</label>
            <textarea id="app-inp-description" name="description" cols="80" rows="10" required></textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Create</button>
            <a href="{{ session()->get('bookmarks.products.create-form', route('products.list')) }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
@endsection