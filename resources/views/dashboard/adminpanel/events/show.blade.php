@extends('dashboard.layouts.app')

@section('title', __('events_show_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('events.show', $event) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('events_show_title') }} №{{ $event->id }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col">

            <h2>{{ $event->description }}</h2>
            {{-- <br>id: {{ $event->id }} --}}
            {{-- <br>user_id: {{ $event->user_id }} --}}
            <br>Исполнитель: {{ $event->getInitiator->name }}
            {{-- <br>type: {{ $event->type }} --}}
            {{-- <br>{{ $event->type }} id: {{ $event->type_id }} --}}
            <br>model: {{ $event->model }} №{{ $event->type_id }}
            {{-- <br>description: {{ $event->description }} --}}
            <br>created_at: {{ $event->created_at }}
            @if ($event->additional_description)
                <br>additional_description: {{ $event->additional_description }}
            @endif


            @if ( unserialize($event->details) )
                <table class="blue_table">
                    <caption>{{ __('list_of_change')}} </caption>
                    <tr>
                        <th>#</th>
                        <th>поле</th>
                        <th>старое значение</th>
                        <th>новое значение</th>
                    </tr>

                    @foreach ( unserialize($event->details) as $property ) 
                        <tr><td>{{ $loop->iteration }}</td><td class="ta_l">{{ $property[0] }}</td><td class="ta_l">{!! $property[1] !!}</td><td class="ta_l">{!! $property[2] !!}</td></tr>
                    @endforeach
                </table>
            @else
                <br>детали события отсутствуют.
            @endif

        </div>
    </div>
@endsection
