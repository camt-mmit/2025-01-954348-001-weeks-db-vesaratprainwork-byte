@extends('shops.main', [
    'title' => 'List',
    'mainClasses' => ['app-ly-max-width'],
])

@section('content')
    <table class="app-cmp-data-list">
        <colgroup>
            <col style="width:6ch;" />
            <col />
            <col />
           
        </colgroup>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($shops as $shop)
                <tr>
                    <td>
                        <a href="{{ route('shops.view', ['shop' => $shop->code]) }}"
                           class="app-cl-code">{{ $shop->code }}</a>
                    </td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
