
<div class="greygrey">category filter</div>
@if($categories->count())
    <ul class="filter navbar-nav mr-auto">
        @foreach( $categories as $category )
            {{-- @if ($category->products->count()) --}}
                <p class="filters">

                    {{ $category->title }} {{-- ({{ $category->products->count() }}) --}}

                    <input 
                        type="checkbox" 
                        name="categories[]" 
                        value="{{ $category->id }}"
                        
                        @if ( !empty($appends['categories']) and in_array($category->id, $appends['categories']) )
                            checked
                        @endif
                    >
                </p>

            {{-- @endif --}}
        @endforeach
    </ul>
@endif



{{-- 

<ul class="navbar-nav mr-auto" id="mainMenu">
    @foreach($categories as $category)
        <li class="nav-item">
            @if ($category->children->count())
                <a href="/products?category={{ $category->slug }}"
                    class="nav-link"
                    id="hasSub-{{ $category->id }}"
                    data-toggle="collapse"
                    data-target="#subnav-{{ $category->id }}"
                    aria-controls="subnav-{{ $category->id }}"
                    aria-expanded="false"
                >{{ $category->title }} ({{ $category->products->count() }})</a>
                <ul class="navbar-collapse collapse"
                    id="subnav-{{ $category->id }}"
                    data-parent="#mainMenu"
                    aria-labelledby="hasSub-{{ $category->id }}"
                >
                    @foreach ($category->children as $subcategory)
                        <li>
                            <a href="/products?category={{ $subcategory->slug }}" class="nav-link">
                                {{ $subcategory->title }} ({{ $subcategory->products->count() }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <a href="/products?category={{ $category->slug }}" class="nav-link">
                    {{ $category->title }} ({{ $category->products->count() }})
                </a>
            @endif
        </li>
    @endforeach
</ul> --}}


