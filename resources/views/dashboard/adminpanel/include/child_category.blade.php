<li>{{ $prefix }}{{ $child_category->name }}</li>
@if ($child_category->subcategories)
    <ul>
        @foreach ($child_category->subcategories as $childCategory)
            @include('dashboard/adminpanel/include/child_category', [
                'child_category' => $childCategory,
                'prefix' => $prefix . $child_category->name . ' > '
            ])
        @endforeach
    </ul>
@endif
