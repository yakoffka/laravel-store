
<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">ПО КАТЕГОРИЯМ</div>
    @if($categories->count())
        @php
            $oForms = '';
        @endphp

        @foreach( $categories as $category )

            {{-- hide empty categories --}}
            @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
                @continue
            @endif
            {{-- /hide empty categories --}}

            {{-- catalog --}}
            @if ( $category->id == 1 )

            {{-- parent categories --}}
            @elseif ( $category->countChildren() ) 
                <input class="filters category" type="checkbox" name="total_{{ $category->id }}" value="1" 
                    onClick="check_{{ $category->id }}(this.form,this.checked)"
                    id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['total_' . $category->id]) )
                        checked
                    @else
                        unchecked
                    @endif
                >
                <label class="filters main_category" for="filter_categories_{{ $category->id }}" 
                    onClick="check_{{ $category->id }}(this.form,['total_{{ $category->id }}'].checked)"
                    title="{{ $category->title }}"
                >
                    {{ $category->name }}
                </label>

                @foreach ( $categories as $i => $subcategory )
                    {{-- hide empty subcategory --}}
                    @if ( !config('settings.show_empty_category') and !$subcategory->countProducts() and !$subcategory->countChildren() )
                        @continue
                    @endif

                    @if ( $subcategory->parent_id == $category->id )
                        @php
                            if ( empty($parent_category_id) or $parent_category_id !== $category->id ) {
                                // $inputs = '';
                                $oForms = '';
                            }
                            // $inputs .= '<input type="checkbox" name="categories[' . $subcategory->id . ']" value="' . $subcategory->id . '">';
                            $oForms .= 'oForm[\'categories[' . $subcategory->id . ']\'].checked = checked;';
                            $parent_category_id = $category->id;
                        @endphp

                        {{-- subcategory --}}
                        <input 
                            class="filters subcategory{{ config('settings.filter_subcategories') ? '' : ' invisible' }}" 
                            type="checkbox" 
                            name="categories[{{ $subcategory->id }}]" 
                            value="{{ $subcategory->id }}"
                            id="filter_categories_{{ $subcategory->id }}"
                            @if ( !empty($appends['categories']) and in_array($subcategory->id, $appends['categories']) )
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
                {{-- <div id="group_{{ $category->id }}">{!! $inputs !!}</div> --}}
                <script type="text/javascript">function check_{{ $category->id }}(oForm,checked){{!! $oForms !!}}</script>

            {{-- categories without subcategory --}}
            @elseif ( $category->parent_id == 1 )
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    id="filter_categories_{{ $category->id }}"
                    @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
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
