 @if ( !($child_category->children()->count()) )
    <option value="{{ $child_category->id }}"
            @if ( isset($product) && $product->category_id === $child_category->id ) selected @endif
    >
        {{ $prefix }}{{ $child_category->title }}
    </option>
@endif
@if ( $child_category->categories )
    @foreach ( $child_category->categories as $childCategory )
        @include('dashboard/adminpanel/include/child_category_option', [
            'child_category' => $childCategory,
            'prefix' => $prefix . $child_category->title . ' > '
        ])
    @endforeach
@endif

