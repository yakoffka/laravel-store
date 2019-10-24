@extends('dashboard.layouts.app')

@section('title', __('customevents_index_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('customevents.index') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('customevents_index_title') }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
    
            {{-- Events --}}
            @if( $customevents->count() )
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
                    @foreach ( $customevents as $customevent )
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ $customevent->id }}</td>
                            {{-- <td>{{ __($customevent->type) }}</td> --}}
                            {{-- <td>{{  __($customevent->model) }}</td> --}}
                            <td>
                                {{-- {{ $customevent->created_at }} --}}
                                <span title="{{ $customevent->created_at }}">{{-- {{ substr($customevent->created_at, 0, 10) }} --}}{{ $customevent->created_at }}</span>
                            </td>
                            <td class="description">
                                {{-- {{ $customevent->description }} --}}
                                {{-- <span title="{{ $customevent->description }}">{{ str_limit($customevent->description, 50) }}</span> --}}
                                <span title="{{ $customevent->description }}">{{ $customevent->description }}</span>
                            </td>
                            @if ( Auth::user()->can('view_orders') )
                                <td>
                                    {{-- {{ $customevent->getInitiator->name }} --}}
                                    <a href="{{ route('customevents.index') }}?users[]={{ $customevent->getInitiator->id }}" 
                                        title="view all events {{ $customevent->getInitiator->name }}"
                                    >
                                        {{ $customevent->getInitiator->name }}
                                    </a>
                                </td>
                            @endif
            
                            <td>
                                <a href="{{ route('customevents.show', $customevent) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
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
