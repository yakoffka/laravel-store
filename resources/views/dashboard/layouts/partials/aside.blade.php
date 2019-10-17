    <div class="col-xs-0 col-sm-4 col-md-3 col-lg-2 aside">
        <div class="d-none d-sm-block">
            @permission('view_adminpanel')
                @include('dashboard.layouts.partials.adminaside')
            @else
            @endpermission
            @if ( !empty($events) )
                @include('dashboard.adminpanel.partials.filters.filter-event')
            @endif
        </div>
    </div>