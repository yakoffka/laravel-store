{{-- {{ dd($categories) }} --}}

    <ul class="navbar-nav mr-auto" id="mainMenu">
        @foreach($categories as $category)
            <li class="nav-item">
                <a href="#"
                    class="nav-link"
                    id="hasSub-{{ $category->id }}"
                    data-toggle="collapse"
                    data-target="#subnav-{{ $category->id }}"
                    aria-controls="subnav-{{ $category->id }}"
                    aria-expanded="false"
                >{{ $category->title }}</a>
                @if ($category->children->count())
                    <ul class="navbar-collapse collapse"
                        id="subnav-{{ $category->id }}"
                        data-parent="#mainMenu"
                        aria-labelledby="hasSub-{{ $category->id }}"
                    >
                        @foreach ($category->children as $subcategory)
                        <li>{{ $subcategory->title }}</li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>



