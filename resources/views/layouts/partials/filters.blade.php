
    <h4>Filters</h4>
    <form class="formfilters" action="{{ route('products.index') }}">

        @include('layouts.partials.filter-manufacturer')
        @include('layouts.partials.filter-category')

        <button type="submit" class="btn btn-outline-success form-control"><i class="fas fa-filter"></i> filter out</button>
    </form>


    @if ( !empty($appends))
        <br>
        <form class="formfilters" action="{{ route('products.index') }}">
            <button type="submit" class="btn btn-outline-danger form-control"><i class="fas fa-eraser"></i> cleare filter</button>
        </form>
    @endif
