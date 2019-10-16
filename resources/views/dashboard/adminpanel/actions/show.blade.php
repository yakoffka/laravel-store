@extends('dashboard.layouts.app')

@section('title', __('actions_show_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('actions.show', $action) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('actions_show_title') }} №{{ $action->id }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col">

            <h2>{{ $action->description }}</h2>
            {{-- <br>id: {{ $action->id }} --}}
            {{-- <br>user_id: {{ $action->user_id }} --}}
            <br>Исполнитель: {{ $action->getInitiator->name }}
            {{-- <br>type: {{ $action->type }} --}}
            {{-- <br>{{ $action->type }} id: {{ $action->type_id }} --}}
            <br>model: {{ $action->model }} №{{ $action->type_id }}
            {{-- <br>description: {{ $action->description }} --}}
            <br>created_at: {{ $action->created_at }}

            @if ( unserialize($action->details) )
                <table class="blue_table">
                    <caption>{{ __('list_of_change')}} </caption>
                    <tr>
                        <th>#</th>
                        <th>поле</th>
                        <th>старое значение</th>
                        <th>новое значение</th>
                    </tr>

                    @foreach ( unserialize($action->details) as $property ) 
                        <tr><td>{{ $loop->iteration }}</td><td class="ta_l">{{ $property[0] }}</td><td class="ta_l">{{ $property[1] }}</td><td class="ta_l">{{ $property[2] }}</td></tr>
                    @endforeach
                </table>
            @else
                <br>детали события отсутствуют.
            @endif

        </div>
    </div>
@endsection
