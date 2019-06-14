@extends('layouts.app')

@section('title', 'Category index')

@section('content')
<div class="container">

    <h1>Category</h1>

    <div class="row">

        <ul>
            @foreach($categories as $category)
                <li>
                    {{ $category->title }}
                    @if ($category->children->count())
                        <ul>
                            @foreach ($category->children as $subcategory)
                            <li>{{ $subcategory->title }}</li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>


        <!-- pagination block -->
        @if($categories->links())
        <div class="row col-sm-12 pagination">{{ $categories->links() }}</div>
        @endif

    </div>
</div>
@endsection
