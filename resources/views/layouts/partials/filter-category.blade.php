@if( $globalCategories->count() > 0 )
    <div class="filter_block left_stylized_checkbox">
        <div class="filter_block_header">{{ __('categories filter') }}</div>
        @php
            $oForms = '';
        @endphp

        @foreach( $globalCategories as $category )

            @if ($category->id === 1)
                @continue;
            @endif

            {{-- categories has subcategories --}}
            @if ($category->subcategories->count())
                <input class="filters category" type="checkbox" name="total_{{ $category->id }}" value="1"
                   onClick="check_{{ $category->id }}(this.form,this.checked)"
                   id="filter_categories_{{ $category->id }}"
                   @if ( !empty($appends['total_' . $category->id]) )
                       checked
                   @endif
                >
                <label class="filters main_category" for="filter_categories_{{ $category->id }}"
                       onClick="check_{{ $category->id }}(this.form,['total_{{ $category->id }}'].checked)"
                       title="{{ $category->uc_title }}">
                    {{ $category->uc_title }}
                </label>

                @foreach ( $category->subcategories as $subcategory )

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
                            @if ( !empty($appends['categories']) && in_array($subcategory->id, $appends['categories'], false) )
                            checked
                            @endif
                        >
                        <label
                            class="filters subcategory{{ config('settings.filter_subcategories') ? '' : ' invisible' }}"
                            for="filter_categories_{{ $subcategory->id }}"
                            title="{{ $subcategory->uc_title }}"
                        >
                            {{ $subcategory->uc_title }}
                        </label>
                    @endif
                @endforeach
                <script type="text/javascript"> function check_{{ $category->id }}(oForm, checked) { {!! $oForms !!} } </script>

            {{-- categories without subcategories --}}
            @elseif ( $category->parent_id === 1 )
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                       id="filter_categories_{{ $category->id }}"
                       @if ( !empty($appends['categories']) && in_array($category->id, $appends['categories'], false) )
                       checked
                    @endif
                >
                <label class="filters main_category" for="filter_categories_{{ $category->id }}"
                       title="{{ $category->uc_title }}">
                    {{ $category->uc_title }}
                </label>
            @endif
        @endforeach
    </div>
@endif
