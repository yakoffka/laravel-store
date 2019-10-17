@extends('dashboard.layouts.app')

@section('title', __('actions_index_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('actions.index') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('actions_index_title') }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
    
            {{-- Actions --}}
            @if( $actions->count() )
                <table class="table blue_table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="30">{{ __('id') }}</th>
                            <th>{{ __('__Type')}}</th>
                            <th>{{ __('__Model')}}</th>
                            <th>{{ __('__Date')}}</th>
                            <th>{{ __('__Description') }}</th>
                            @if ( Auth::user()->can('view_orders') )
                                <th>{{ __('__Initiator')}}</th>
                            @endif
                            <th width="30"><div class="verticalTableHeader ta_c">actions</div></th>
                        </tr>
                    </thead>
                    @foreach ( $actions as $action )
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ $action->id }}</td>
                            <td>{{ __($action->type) }}</td>
                            <td>{{  __($action->model) }}</td>
                            <td>
                                {{-- {{ $action->created_at }} --}}
                                <span title="{{ $action->created_at }}">{{-- {{ substr($action->created_at, 0, 10) }} --}}{{ $action->created_at }}</span>
                            </td>
                            <td class="description">
                                {{-- {{ $action->description }} --}}
                                {{-- <span title="{{ $action->description }}">{{ str_limit($action->description, 50) }}</span> --}}
                                <span title="{{ $action->description }}">{{ $action->description }}</span>
                            </td>
                            @if ( Auth::user()->can('view_orders') )
                                <td>
                                    {{-- {{ $action->getInitiator->name }} --}}
                                    <a 
                                        href="
                                        {{-- {{ route('actions.user', $action->getInitiator) }} --}}
                                        " 
                                        title="view all actions {{ $action->getInitiator->name }}"
                                    >
                                        {{ $action->getInitiator->name }}{{ $action->getInitiator->id }}
                                    </a>
                                </td>
                            @endif
            
                            <td>
                                <a href="{{ route('actions.show', $action) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                            </td>
            
                        </tr>
                    @endforeach
                </table>
            @else
                Активность не зафиксирована.
            @endif
            {{-- /Actions --}}

        </div>
    </div>
@endsection
