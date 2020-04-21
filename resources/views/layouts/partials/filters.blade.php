@if ( config('settings.filter_categories' ) or config('settings.filter_manufacturers') )

    <form class="form_filters" action="{{ route('products.index') }}">
        @if ( config('settings.filter_manufacturers' ) )
            @include('layouts.partials.filter-manufacturer')
        @endif

        @if ( config('settings.filter_categories' ) )
            @include('layouts.partials.filter-category')
        @endif

        <button type="submit" class="btn btn-outline-success form-control">
            <i class="fas fa-filter"></i> {{ __('filter_out')}}
        </button>
    </form>


    @if ( !empty($appends['manufacturers']) or !empty($appends['categories']) )
        <form class="form_filters" action="{{ route('products.index') }}">
            <button type="submit" class="btn btn-outline-danger form-control">
                <i class="fas fa-eraser"></i> {{ __('clear_filter')}}
            </button>
        </form>
    @endif

@endif
