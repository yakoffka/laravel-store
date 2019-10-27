@extends('dashboard.layouts.app')

@section('title', __('customevents_show_title'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('customevents.show', $customevent) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('customevents_show_title') }} №{{ $customevent->id }}</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col">

            <h2>{{ $customevent->type }} {{ $customevent->model }} id={{ $customevent->model_id }}</h2>
            {{-- <br>id: {{ $customevent->id }} --}}
            {{-- <br>user_id: {{ $customevent->user_id }} --}}
            <br>Исполнитель: {{ $customevent->getInitiator->name }}
            {{-- <br>type: {{ $customevent->type }} --}}
            <br>type: {{ $customevent->type }}
            <br>model: {{ $customevent->model }} №{{ $customevent->model_id }}
            {{-- <br>description: {{ $customevent->description }} --}}
            <br>created_at: {{ $customevent->created_at }}
            @if ($customevent->additional_description)
                <br>additional_description: {{ $customevent->additional_description }}
            @endif


            @if ( unserialize($customevent->details) )
                <table class="blue_table">
                    <caption>{{ __('list_of_change')}} </caption>
                    <tr>
                        <th>#</th>
                        <th>поле</th>
                        <th>старое значение</th>
                        <th>новое значение</th>
                    </tr>

                    @foreach ( unserialize($customevent->details) as $property ) 
                        <tr><td>{{ $loop->iteration }}</td><td class="ta_l">{{ $property[0] }}</td><td class="ta_l">{!! $property[1] !!}</td><td class="ta_l">{!! $property[2] !!}</td></tr>
                    @endforeach
                </table>
            @else
                <br>детали события отсутствуют.
            @endif
            
            <div class="center">
                <a class="btn btn-outline-primary form-control col-5" href="{{ route('customevents.show', $customevent->id-1) }}">prew event</a>
                <a class="btn btn-outline-primary form-control col-5" href="{{ route('customevents.show', $customevent->id+1) }}">next event</a>
            </div>

        </div>
    </div>
@endsection
