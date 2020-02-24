<div class="col-lg-4 col-md-6 product_card_bm">
    <div class="category grid shadoweffect2">

        <div class="block_number block_number_right">
            @if ($category->products->count())
                {{ str_pad($category->products->count(), 2, '0', STR_PAD_LEFT) }}
            @elseif ($category->children->count())
                {{ str_pad($category->children->count(), 2, '0', STR_PAD_LEFT) }}
            @else
                00
            @endif
        </div>

        <a href="{{ route('categories.show', ['category' => $category->id]) }}">
            <div class="card-img-top b_image"
                 style="background-image: url({{ asset('storage') }}{{$category->full_image_path}});">

                <div class="dummy perc100"></div>
                <div class="element"></div>
            </div>
        </a>

        <a data-ripple href="{{ route('categories.show', ['category' => $category->id]) }}" class="btn ta_l" title="{{ $category->title }}">
            {{ $category->name }}
            <br><span>
                @if ($category->products->count())
                    {{ trans_choice('categories.numproducts', $category->value_for_trans_choice_products, ['value' => $category->products->count()]) }}
                @elseif ($category->children->count())
                    {{ trans_choice('categories.numpcategories', $category->value_for_trans_choice_children, ['value' => $category->children->count()]) }}
                @else
                    категория пуста
                @endif
            </span>
        </a>

    </div>
</div>
