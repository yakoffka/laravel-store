{{-- {{ dd($categories) }} --}}
<h4>Catalog</h4>

{{-- {{ dd($categories) }} --}}

@if($categories->count())
<ul class="navbar-nav mr-auto" id="mainMenu">
    @foreach($categories as $category)
        <li class="nav-item">
            @if ($category->children->count())
                <a href="/products?categories[]={{ $category->id }}"
                    class="nav-link"
                    id="hasSub-{{ $category->id }}"
                    data-toggle="collapse"
                    data-target="#subnav-{{ $category->id }}"
                    aria-controls="subnav-{{ $category->id }}"
                    aria-expanded="false"
                {{-- >{{ $category->title }} ({{ $category->products->count() }})</a> --}}
                >{{ $category->title }} ></a>
                <ul class="navbar-collapse collapse"
                    id="subnav-{{ $category->id }}"
                    data-parent="#mainMenu"
                    aria-labelledby="hasSub-{{ $category->id }}"
                >
                    @foreach ($category->children as $subcategory)
                        <li>
                            <a href="/products?categories[]={{ $subcategory->id }}" class="nav-link">
                                {{ $subcategory->title }} ({{ $subcategory->products->count() }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <a href="/products?categories[]={{ $category->id }}" class="nav-link">
                    {{ $category->title }} ({{ $category->products->count() }})
                </a>
            @endif
        </li>
    @endforeach
</ul>
@endif
