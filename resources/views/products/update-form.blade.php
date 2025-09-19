@extends('products.main', [
    'title' => $product->code,
])

@section('content')
    <form action="{{ route('products.update', [
        'product' => $product->code,
    ]) }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" value="{{ $product->code }}" required />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $product->name }}" required />

            <label for="app-category">Category</label>
    <select name="category">
    @foreach($categories as $c)
        <option value="{{ $c->code }}" @selected($product->category && $product->category->code === $c->code)>
            [{{ $c->code }}] {{ $c->name }}
        </option>
    @endforeach
</select>

            <label for="app-inp-price">Price</label>
            <input type="number" id="app-inp-price" name="price" value="{{ $product->price }}" step="any" required />

            <label for="app-inp-description">Description</label>
            <textarea id="app-inp-description" name="description" cols="80" rows="10" required>{{ $product->description }}</textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Update</button>
        </div>
    </form>

@endsection