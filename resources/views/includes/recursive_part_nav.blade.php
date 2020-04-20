@foreach($categories as $category)

    @if ($sharedFlatCategories->find($category->id)->subcategories_count)
        <a href="#category_{{ $category->id }}" class="list-group-item" data-toggle="collapse"
           data-parent="#{{ $data_parent }}">
            {{ ($category->uc_title) }} <i class="right fas fa-chevron-down"></i>
        </a>
        <div class="b_bottom"></div>
        <div class="collapse {{ $sub_class }}" id="category_{{ $category->id }}">
            @include('includes.recursive_part_nav', [
                'categories' => $sharedFlatCategories->find($category->id)->subcategories,
                'data_parent' => 'category_' . $category->id,
                'sub_class' => 'sub_' . $sub_class,
            ])
        </div>

    @elseif ($sharedFlatCategories->find($category->id)->products_count)
        <a href="{{ route('categories.show', ['category' => $category->id]) }}" class="list-group-item"
           data-parent="#{{ $data_parent }}">
            {{$category->uc_title}} <i class="right fas fa-chevron-right"></i>
        </a>
        <div class="b_bottom"></div>

    @else
        <a href="#" class="list-group-item" data-parent="#{{ $data_parent }}">
            {{$category->uc_title}} [empty]
        </a>
        <div class="b_bottom"></div>
    @endif
@endforeach

