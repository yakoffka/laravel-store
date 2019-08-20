        <form class="searchform" action="{{ route('search') }}" method="GET" role="search">
            {{-- <input type="text" placeholder="Искать здесь..."> --}}
            <input 
                {{-- style="width:100%; margin-top:5px; height:2em;"  --}}
                type="search" 
                {{-- class="input-sm form-control"  --}}
                name="query" 
                placeholder="Поиск товара"
                value="{{ $query ?? '' }}"
            >
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>