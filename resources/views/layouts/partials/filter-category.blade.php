
<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">КАТЕГОРИИ</div>
    @if($categories->count())

        {{-- @foreach( $categories as $category )
            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                id="filter_categories_{{ $category->id }}"
                @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
                    checked
                @endif
            >
            <label class="filters" for="filter_categories_{{ $category->id }}">
                {{ $category->title }}
            </label>
        @endforeach --}}


        @foreach( $categories as $category )

            @if ( !config('settings.filter_subcategories') and $category->parent_id == 1 )
            
                {{-- all category and subcategory --}}
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
                        checked
                    @endif
                >
                <label class="filters" for="filter_categories_{{ $category->id }}">
                    {{ $category->title }}
                </label>

            @elseif ( config('settings.filter_subcategories') )

                {{-- only category --}}
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
                        checked
                    @endif
                >
                <label class="filters" for="filter_categories_{{ $category->id }}">
                    {{ $category->title }}
                </label>

            @endif
        @endforeach

    @endif
</div>
