    <div class="row product_buttons">

    <div class="col-sm-3">
        <a href="{{ route('products.show', ['product' => $product->id]) }}"
           class="btn btn-outline-info">
            <i class="fas fa-layer-group" title="{{__('compare')}}"></i>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="{{ route('products.show', ['product' => $product->id]) }}"
           class="btn btn-outline-info">
            <i class="fas fa-heart" title="{{__('to favorite')}}"></i>
        </a>
    </div>

    @if ( config('settings.display_cart') )
        <div class="col-sm-3">
            <a href="{{ route('cart.add-item', ['product' => $product->id]) }}"
               class="btn btn-outline-success">
                <i class="fas fa-cart-plus" title="{{__('to cart')}}"></i>
            </a>
        </div>
    @endif

    @auth
        @if (auth()->user()->can('edit_products'))
            <div class="col-sm-3">
                <a href="{{ route('products.edit', ['product' => $product->id]) }}"
                   class="btn btn-outline-success">
                    <i class="fas fa-pen-nib"></i>
                </a>
            </div>
        @endif

        @if (auth()->user()->can('delete_products'))
            <div class="col-sm-3">
                @modalConfirmDestroy([
                'btn_class' => 'btn btn-outline-danger form-control',
                'cssId' => 'delete_',
                'item' => $product,
                'action' => route('products.destroy', ['product' => $product->id]),
                ])
            </div>
        @endif
    @endauth

</div>
