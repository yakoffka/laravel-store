@if($manufacturers->count())
{{-- // slug!!!! --}}
    <h4>filter</h4>
    <ul class="filter navbar-nav mr-auto">
        @foreach($manufacturers as $manufacturer)
            <li>
                <a href="/products?manufacturer={{ $manufacturer->name }}" class="nav-link">
                    {{ $manufacturer->title }}
                </a>
            </li>
        @endforeach
    </ul>

@endif
