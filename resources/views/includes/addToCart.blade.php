<a href="{{ !empty($inactive) ? '#' : route('cart.add-item', ['product' => $product_id]) }}" class="btn btn-outline-{{ !empty($inactive) ? 'secondary' : 'success' }}">
    <i class="fas fa-cart-plus"></i> to cart
</a>
