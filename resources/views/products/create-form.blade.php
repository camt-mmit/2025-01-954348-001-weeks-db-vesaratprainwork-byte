@extends('products.main', [
    'title' => 'Create',
    'mainClasses' => ['app-ly-max-width'],
])

@section('header')
    {{-- แถบลิงก์ด้านบน (เหมือน lab) พร้อมปุ่ม Back --}}
    <div class="app-cmp-links-bar">
        <nav>
            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('products.list') }}">
                        &lt; Back
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('products.create') }}" method="post" class="app-cmp-form">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-code">Code</label>
            <input type="text" id="app-code" name="code" value="{{ old('code') }}" required />
        </div>

        <div class="app-cmp-form-detail">
            <label for="app-name">Name</label>
            <input type="text" id="app-name" name="name" value="{{ old('name') }}" required />
        </div>

        <div class="app-cmp-form-detail">
            <label for="app-category">Category</label>
            <select id="app-category" name="category_id">
                <option value="">--- Please Select Category ---</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                        [{{ $cat->code }}] {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="app-cmp-form-detail">
            <label for="app-price">Price</label>
            <input type="number" id="app-price" name="price" value="{{ old('price') }}" step="any" min="0" />
        </div>

        <div class="app-cmp-form-detail">
            <label for="app-description">Description</label>
            <textarea id="app-description" name="description" rows="8">{{ old('description') }}</textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Create</button>
            <a href="{{ route('products.list') }}">
                <button type="button" class="accent">Cancel</button>
            </a>
        </div>
    </form>
@endsection



