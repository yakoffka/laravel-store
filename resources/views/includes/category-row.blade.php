
    <tr class="{{ ($category->isPublish()) ? '' : 'gray' }}{{ $category->parent_id === 1 ? ' main_category' : '' }}">
        <td>{{ $category->id }}</td>
        <td class="ta_l{{ $category->parent_id === 1 ? '' : ' subcategory' }}">{{ $category->name }}</td>
        <td>{{ $category->parent->id ?? '-' }}</td>
        <td>{{ $category->sort_order }}</td>

        {{-- publish --}}
        <td>
            @if ( $category->publish )
                <i class="far fa-eye"></i>
            @else
                <i class="far fa-eye-slash"></i>
            @endif
        </td>

        {{-- parent publish --}}
        <td>
            @if ( $category->parent->publish )
                <i class="far fa-eye"></i>
            @else
                <i class="far fa-eye-slash"></i>
            @endif
        </td>

        <td>
            @if ( $category->imagepath )
                <div class="card-img-top b_image"
                    style="background-image: url({{ asset('storage') }}/images/categories/{{$category->uuid}}/{{$category->imagepath}});">
            @else
                <div class="card-img-top b_image"
                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});">
            @endif
                <div class="dummy perc100"></div>
                <div class="element"></div>
            </div>
        </td>

        <td>{{ $category->subcategories->count() }}</td>
        <td>{{ $category->products->count() }}</td>

        {{-- actions --}}
        <td>
            {{-- view --}}
            <a href="{{ route('categories.adminshow', $category) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>

            {{-- edit --}}
            <a href="{{ route('categories.edit', ['category' => $category->id]) }}"
                class="btn btn-outline-success">
                <i class="fas fa-pen-nib"></i>
            </a>

            {{-- delete --}}
            @if ( $category->products->count() or $category->subcategories->count() )
                <button type="button"
                    class="btn btn-outline-secondary align-self-center"
                    title="Категория {{ $category->name }} не может быть удалена, пока в ней находятся товары или подкатегории."
                >
                    <i class="fas fa-trash"></i>
                </button>
            @else
                @permission('delete_categories')
                    @modalConfirmDestroy([
                        'btn_class' => 'btn btn-outline-danger align-self-center',
                        'cssId' => 'delete_',
                        'item' => $category,
                        'type_item' => 'категорию',
                        'action' => route('categories.destroy', $category),
                    ])
                @endpermission
            @endif
        </td>
        {{-- /actions --}}
    </tr>

