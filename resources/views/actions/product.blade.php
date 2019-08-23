@extends('layouts.app')

@section('title', 'actions')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{ Breadcrumbs::render('actions.product', $product) }}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>

    <a href=""></a>

    <h1>History of Actions product "{{ $product->name }}"</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">
    
            {{-- Actions --}}
            @if( $actions->count() )
                <h2 id="actions">table all actions</h2>
                @include('layouts.partials.actions')
            @else
                Активность не зафиксирована.
            @endif
            {{-- /Actions --}}

        </div>
    </div>
@endsection
