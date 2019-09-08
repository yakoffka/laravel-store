
    <tr class="{{ !$category->visible ? 'gray' : '' }}{{ $category->parent_id == 1 ? ' main_category' : '' }}">
        <td>{{ $category->id }}</td>
        <td class="ta_l{{ $category->parent_id == 1 ? '' : ' subcategory' }}">{{ $category->name }}</td>
        <td>{{ $category->parent->id ?? '-' }}</td>
        <td>{{ $category->sort_order }}</td>

        {{-- <td>{{ $category->visible }}</td> --}}
        <td>
            @if ( $category->visible )
                <i class="far fa-eye"></i>
            @else
                <i class="far fa-eye-slash"></i>
            @endif
        </td>

        <td>{{ $category->countProducts() }}</td>
        <td>{{ $category->countChildren() }}</td>

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
            @if ( $category->countProducts() or $category->countChildren() )
                <button type="button" 
                    class="btn btn-outline-second align-self-center" 
                    title="Категория {{ $category->name }} не может быть удалена, пока в ней находятся товары или подкатегории."
                >
                    <i class="fas fa-trash"></i>
                </button>
            @else
                @permission('delete_categories')
                    @modalConfirmDestroy([
                        'btn_class' => 'btn btn-outline-danger align-self-center',
                        'cssId' => 'delele_',
                        'item' => $category,
                        'type_item' => 'категорию',
                        'action' => route('categories.destroy', $category), 
                    ])
                @endpermission
            @endif

        </td>
    </tr>

