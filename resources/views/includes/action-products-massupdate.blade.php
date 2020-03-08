        <button type="button" class="btn btn-outline-{{ $b_class }} col-sm-2" data-toggle="modal" data-target="#modal_mass_{{ $value }}">
            <i class="fas {{ $i_class }}"></i> {{ $b_label }}
        </button>
        <div class="modal fade" id="modal_mass_{{ $value }}" tabindex="-1" role="dialog" aria-labelledby="modal_mass_{{ $value }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_mass_{{ $value }}Label">подтверждение действия</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ta_l">
                        <p>Запрошенная Вами операция требует подтверждения.</p>
                        @if( $value === 'replace' )
                            <div class="form-group">
                                <label for="category_id">{{ $mess }} 2</label>
                                <select name="category_id" id="category_id">
                                    @foreach ( $categories as $category )
                                        @if ( $category->children->count() !== 0 ){{-- категории с подкатегориями --}}
                                            @foreach ( $category->children as $subcategory )
                                                @if ( $subcategory->children->count() === 0 ){{-- подкатегории без подкатегорий --}}
                                                    <option value="{{ $subcategory->id }}">{{ $category->name }} > {{ $subcategory->title }}</option>
                                                @endif
                                            @endforeach
                                        @elseif ( $category->products->count() === 0 ){{-- категории без товаров (и без подкатегорий) --}}
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <p>{{ $mess }}</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="action" value="{{ $value }}" class="btn btn-outline-{{ $b_class }} form-control">
                            <i class="fas {{ $i_class }}"></i> {{ $b_label }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
