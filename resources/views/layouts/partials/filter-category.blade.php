<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">ПО КАТЕГОРИЯМ</div>

    @if( $globalCategories->count() > 0 )
        @php
            $oForms = '';
        @endphp

        @foreach( $globalCategories as $category )

            @if ($category->id === 1)
                @continue;
            @endif

            @if ($category->children->count()){{-- parent categories whith subcategories --}}
                <input class="filters category" type="checkbox" name="total_{{ $category->id }}" value="1"
                    onClick="check_{{ $category->id }}(this.form,this.checked)"
                    id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['total_' . $category->id]) )
                        checked
                    @endif
                >
                <label class="filters main_category" for="filter_categories_{{ $category->id }}"
                    onClick="check_{{ $category->id }}(this.form,['total_{{ $category->id }}'].checked)"
                    title="{{ $category->title }}"
                >
                    {{ $category->name }}
                </label>

                @foreach ( $category->children as $subcategory )

                    {{-- subcategories --}}
                    @if ( $subcategory->parent_id === $category->id )
                        @php
                            if ( empty($parent_category_id) or $parent_category_id !== $category->id ) {
                                $oForms = '';
                            }
                            $oForms .= 'oForm[\'categories[' . $subcategory->id . ']\'].checked = checked;';
                            $parent_category_id = $category->id;
                        @endphp
                        <input
                            class="filters subcategory{{ config('settings.filter_subcategories') ? '' : ' invisible' }}"
                            type="checkbox"
                            name="categories[{{ $subcategory->id }}]"
                            value="{{ $subcategory->id }}"
                            id="filter_categories_{{ $subcategory->id }}"
                            @if ( !empty($appends['categories']) && in_array($subcategory->id, $appends['categories']) )
                                checked
                            @endif
                        >
                        <label
                            class="filters subcategory{{ config('settings.filter_subcategories') ? '' : ' invisible' }}"
                            for="filter_categories_{{ $subcategory->id }}"
                            title="{{ $subcategory->title }}"
                        >
                            {{ $subcategory->name }}
                        </label>
                    @endif
                @endforeach
                <script type="text/javascript"> function check_{{ $category->id }}(oForm, checked) { {!! $oForms !!} } </script>

            {{-- categories without subcategories --}}
            @elseif ( $category->parent_id === 1 )
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                       id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['categories']) && in_array($category->id, $appends['categories']) )
                        checked
                    @endif
                >
                <label class="filters main_category"
                       for="filter_categories_{{ $category->id }}"
                       title="{{ $category->title }}"
                >
                    {{ $category->name }}
                </label>
            @endif
        @endforeach
    @endif
</div>
