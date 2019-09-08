@if ( config('settings.filter_categories' ) or config('settings.filter_manufacturers') )
    
    <h4 class="ta_c">фильтры</h4>

        <form class="formfilters" action="{{ route('products.index') }}">

            @if ( config('settings.filter_manufacturers' ) )
                @include('layouts.partials.filter-manufacturer')
            @endif

            @if ( config('settings.filter_categories' ) )
                @include('layouts.partials.filter-category')
            @endif

            <button type="submit" class="btn btn-outline-success form-control"><i class="fas fa-filter"></i> filter out</button>
        </form>


    @if ( !empty($appends['manufacturers']) or !empty($appends['categories']) )
        <form class="formfilters" action="{{ route('products.index') }}">
            <button type="submit" class="btn btn-outline-danger form-control"><i class="fas fa-eraser"></i> cleare filter</button>
        </form>
    @endif

@endif
