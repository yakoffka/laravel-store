@extends('dashboard.layouts.app')

@section('title', __('events_index_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('events.index') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('events_index_title') }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
    
            {{-- Events --}}
            @if( $events->count() )
                <table class="table blue_table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="30">{{ __('id') }}</th>
                            {{-- <th>{{ __('__Type')}}</th> --}}
                            {{-- <th>{{ __('__Model')}}</th> --}}
                            <th>{{ __('__Date')}}</th>
                            <th>{{ __('__Description') }}</th>
                            @if ( Auth::user()->can('view_orders') )
                                <th>{{ __('__Initiator')}}</th>
                            @endif
                            <th width="30"><div class="verticalTableHeader ta_c">actions</div></th>
                        </tr>
                    </thead>
                    @foreach ( $events as $event )
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ $event->id }}</td>
                            {{-- <td>{{ __($event->type) }}</td> --}}
                            {{-- <td>{{  __($event->model) }}</td> --}}
                            <td>
                                {{-- {{ $event->created_at }} --}}
                                <span title="{{ $event->created_at }}">{{-- {{ substr($event->created_at, 0, 10) }} --}}{{ $event->created_at }}</span>
                            </td>
                            <td class="description">
                                {{-- {{ $event->description }} --}}
                                {{-- <span title="{{ $event->description }}">{{ str_limit($event->description, 50) }}</span> --}}
                                <span title="{{ $event->description }}">{{ $event->description }}</span>
                            </td>
                            @if ( Auth::user()->can('view_orders') )
                                <td>
                                    {{-- {{ $event->getInitiator->name }} --}}
                                    <a 
                                        href="
                                        {{-- {{ route('events.user', $event->getInitiator) }} --}}
                                        " 
                                        title="view all events {{ $event->getInitiator->name }}"
                                    >
                                        {{ $event->getInitiator->name }}
                                    </a>
                                </td>
                            @endif
            
                            <td>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                            </td>
            
                        </tr>
                    @endforeach
                </table>
            @else
                Активность не зафиксирована.
            @endif
            {{-- /Events --}}

        </div>
    </div>
@endsection
