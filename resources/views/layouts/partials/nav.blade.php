<h4 class="ta_c">{{ __('Categories')}}</h4>

@if($globalCategories->count())
<ul class="navbar-nav mr-auto" id="mainMenu">

    @foreach($globalCategories as $category)
        <li class="nav-item" title="{{ $category->uc_title }}">
            @if ($category->children->count())
                <a href="{{ route('categories.show', ['category' => $category->id]) }}" class="nav-link" id="hasSub-{{ $category->id }}" data-toggle="collapse" data-target="#subnav-{{ $category->id }}" aria-controls="subnav-{{ $category->id }}" aria-expanded="false">
                    {{ $category->uc_title }} >
                </a>
                <ul class="navbar-collapse collapse subnav" id="subnav-{{ $category->id }}" data-parent="#mainMenu" aria-labelledby="hasSub-{{ $category->id }}">
                    @foreach ($category->children as $subcategory)
                        <li title="{{ $subcategory->uc_title }}">
                            <a href="{{ route('categories.show', ['category' => $subcategory->id]) }}"
                               class="nav-link">
                                {{ $subcategory->uc_title }} ({{ $subcategory->publishedProducts->count() }})
                            </a>
                        </li>
                    @endforeach

                    <li><a href="{{ route('categories.show', ['category' => $category->id]) }}">show all</a></li>
                </ul>
            @else
                <a href="/products?categories[]={{ $category->id }}" class="nav-link">
                    {{ $category->uc_title }} ({{ $category->publishedProducts->count() }})
                </a>
            @endif
        </li>
    @endforeach

</ul>
@endif
