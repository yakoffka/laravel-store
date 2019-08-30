
{{-- <div class="filter_block right_stylized_checkbox"> --}}
<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">КАТЕГОРИИ</div>
    @if($categories->count())

        @foreach( $categories as $category )
            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                id="filter_categories_{{ $category->id }}"
                @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
                    checked
                @endif
            >
            <label class="filters" for="filter_categories_{{ $category->id }}">
                {{ $category->title }}{{-- ({{ $category->products->count() }}) --}}
            </label>

        @endforeach

    @endif
</div>
