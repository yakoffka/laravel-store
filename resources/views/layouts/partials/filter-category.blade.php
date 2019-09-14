
<div class="filter_block left_stylized_checkbox">
    <div class="filter_block_header">КАТЕГОРИИ</div>
    @if($categories->count())

{{--    @foreach( $categories as $category )

            @if ( !config('settings.filter_subcategories') and $category->parent_id == 1 )
            
                <!-- only category -->
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

                <!-- all: category and subcategory -->
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
        @endforeach --}}
        @foreach( $categories as $category )

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
                    onClick="check_{{ $category->id }}(this.form,['total_{{ $category->id }}'].checked)">
                    {{ $category->title }}
                </label>

                @foreach ( $categories as $i => $subcategory )
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
                        @if ( config('settings.filter_subcategories') )
                            <input class="filters subcategory" type="checkbox" 
                                name="categories[{{ $subcategory->id }}]" 
                                value="{{ $subcategory->id }}"
                                id="filter_categories_{{ $subcategory->id }}"
                                @if ( !empty($appends['categories']) and in_array($subcategory->id, $appends['categories']) )
                                    checked
                                @endif
                            >
                            <label class="filters subcategory" for="filter_categories_{{ $subcategory->id }}">
                                {{ $subcategory->title }}
                            </label>
                        @endif
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
                <label class="filters main_category" for="filter_categories_{{ $category->id }}">
                    {{ $category->title }}
                </label>
            @endif
        @endforeach
    @endif
</div>
