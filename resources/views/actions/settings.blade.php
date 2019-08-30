@extends('layouts.app')

@section('title', 'actions')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('actions.settings') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 searchform">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Actions</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
    
            {{-- Actions --}}
            @if( $actions->count() )
                <h2 id="actions">Table of cange settings</h2>
                @include('layouts.partials.actions')
            @else
                Активность не зафиксирована.
            @endif
            {{-- /Actions --}}

        </div>
    </div>
@endsection
