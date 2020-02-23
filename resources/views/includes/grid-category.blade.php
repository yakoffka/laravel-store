<div class="col-lg-4 col-md-6 product_card_bm">
    <div class="category grid shadoweffect2">

        {{-- <div class="title_green"><h3 title="{{ $category->title }}">{{ $category->name }}</h3></div> --}}

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
        {{-- <button class="ripplebutton" data-ripple>Demo button 1</button> --}}

    </div>


    {{-- <div class="card">

        <h2 class="product_card_h2">
            <a href="{{ route('categories.show', ['category' => $category->id]) }}">
                {{ $category->title }}
            </a>
        </h2>

        <a href="{{ route('categories.show', ['category' => $category->id]) }}">
            @if($category->imagepath)
                <div class="card-img-top b_image"
                    style="background-image: url({{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->imagepath}});">
            @else
                <div class="card-img-top b_image"
                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});">
            @endif

                <div class="dummy perc50"></div>
                <div class="element"></div>
            </div>
        </a>


        <div class="card-body">

            <div class="row product_buttons center">

                @guest

                    <div class="col-sm-12">
                        <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> view all
                        </a>
                    </div>

                @else

                    @if ( Auth::user()->can( ['view_categories', 'edit_categories', 'delete_categories'], true ) )
                        <div class="col-sm-4">
                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('categories.edit', ['category' => $category->id]) }}"
                                class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <!-- form delete category -->
                            <form action="{{ route('categories.destroy', ['category' => $category->id]) }}"
                                method="POST">
                                @csrf

                                @method("DELETE")

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                    @elseif ( Auth::user()->can( ['view_categories', 'edit_categories'], true ) )

                        <div class="col-sm-6">
                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                        <div class="col-sm-6">
                            <a href="{{ route('categories.edit', ['category' => $category->id]) }}"
                                class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        </div>

                    @elseif ( Auth::user()->can( 'view_categories' ) )

                        <div class="col-sm-12">
                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> view
                            </a>
                        </div>

                    @endif

                @endguest

            </div>
        </div>
    </div> --}}
</div>
