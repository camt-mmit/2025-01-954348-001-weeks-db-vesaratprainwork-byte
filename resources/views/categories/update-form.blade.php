@extends('categories.main', [
    'title' => $category->code,
    'titleClasses' => ['app-cl-code'],
])

@section('content')
    <form action="{{ route('categories.update', [
        'category' => $category->code,
    ]) }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" value="{{ $category->code }}" required class="app-cl-code" />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $category->name }}" required />

            <label for="app-inp-description">Description</label>
            <textarea id="app-inp-descruotuib" name="description" cols="80" rows="10" required>{{ $category->description }}</textarea>
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Update</button>
            <a
                href="{{ route('categories.view', [
                    'category' => $category->code,
                ]) }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
@endsection