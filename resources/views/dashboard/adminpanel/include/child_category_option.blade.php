@if ( !($child_category->subcategories()->count()) )
    <option value="{{ $child_category->id }}"
            @if ( isset($product) && $product->category_id === $child_category->id ) selected @endif
    >
        {{ $prefix }}{{ $child_category->title }}
    </option>
@endif
@if ( $child_category->subcategories )
    @foreach ( $child_category->subcategories as $childCategory )
        @include('dashboard/adminpanel/include/child_category_option', [
            'child_category' => $childCategory,
            'prefix' => $prefix . $child_category->title . ' > '
        ])
    @endforeach
@endif

