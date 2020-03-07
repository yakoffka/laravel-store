<tr class="{{ (!$product->isPublish()) ? 'gray' : ''}}">
    <td class="ta_c left_stylized_checkbox">
        <input
            form="products_massupdate"
            type="checkbox"
            name="products[{{ $product->id }}]"
            value="{{ $product->id }}"
            id="product_checkbox_{{ $product->id }}"
        >
        <label class="empty_label" for="product_checkbox_{{ $product->id }}">{{ $product->id }}</label>
    </td>
    <td class="ta_l">{{ $product->name }}</td>
    {{-- <td>{{ $product->slug }}</td> --}}
    {{-- <td class="ta_c">{{ $product->manufacturer_id }}</td> --}}

    {{-- publish --}}
    <td class="ta_c w30">
        @if ( $product->publish )
            <i class="far fa-eye"></i>
        @else
            <i class="far fa-eye-slash"></i>
        @endif
    </td>

    {{-- category publish --}}
    <td class="ta_c w30">
        @if ( $product->category->publish )
            <i class="far fa-eye"></i>
        @else
            <i class="far fa-eye-slash"></i>
        @endif
    </td>

    {{-- parent category publish --}}
    <td class="ta_c w30">
        @if ( $product->category->parentSeeable() )
            <i class="far fa-eye"></i>
        @else
            <i class="far fa-eye-slash"></i>
        @endif
    </td>

    {{-- <td class="ta_c">{{ $product->category_id }}</td> --}}
    {{-- @if ( !empty($category) )
        <td class="ta_c" title="{{ $product->category->title }}">{{ $product->category_id }}</td>
    @endif --}}
    <td class="ta_c" title="{{ $product->category->title }}">{{ $product->category_id }}</td>


    <td>
        <a href="{{ route('products.show', ['product' => $product->id]) }}">
            <div class="card-img-top b_image"
                 style="background-image: url({{ asset('storage') }}{{$product->full_image_path_s}});">
                <div class="dummy perc100"></div>
                <div class="element"></div>
            </div>
        </a>
    </td>

    {{-- <td>{{ $product->materials }}</td> --}}
    {{-- <td>{{ $product->description }}</td> --}}
    {{-- <td class="ta_c">{{ $product->date_manufactured }}</td> --}}
    <td>{{ $product->price }}</td>
    {{-- <td class="ta_c">{{ $product->added_by_user_id }}</td> --}}
    {{-- <td class="ta_c">{{ $product->created_at }}</td>
    <td class="ta_c">{{ $product->updated_at }}</td> --}}
    {{-- <td>{{ $product->images->count() }}</td> --}}
    {{-- <td class="ta_c">coming soon</td> --}}

    {{-- actions --}}
    <td class="actions4">

        {{-- view --}}
        <a href="{{ route('products.adminshow', $product) }}"
           class="btn btn-outline-primary" title="{{ __('show_action') }}">
            <i class="fas fa-eye"></i>
        </a>

        {{-- edit --}}
        <a href="{{ route('products.edit', ['product' => $product->id]) }}"
           class="btn btn-outline-success" title="{{ __('edit_action') }}">
            <i class="fas fa-pen-nib"></i>
        </a>

        {{-- copy --}}
        <a href="{{ route('products.copy', ['product' => $product->id]) }}"
           class="btn btn-outline-primary" title="{{ __('copy_action') }}">
            <i class="fas fa-copy"></i>
        </a>

        {{-- delete --}}
        @permission('delete_products')
        @modalConfirmDestroy([
        'btn_class' => 'btn btn-outline-danger align-self-center',
        'cssId' => 'delete_action',
        'item' => $product,
        'type_item' => 'товар',
        'action' => route('products.destroy', $product),
        ])
        @endpermission

    </td>
    {{-- /actions --}}
</tr>
