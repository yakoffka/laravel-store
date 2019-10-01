@extends('layouts.app')

@section('title', 'task')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( empty($directive) )
                {{ Breadcrumbs::render('tasks.show', $task) }}
            @else
                {{ Breadcrumbs::render('directives.show', $task) }}
            @endif
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            <div class="d-none d-md-block">@include('layouts.partials.searchform')</div>
        </div>
    </div>

    <h1>Просмотр @if ( empty($directive) ) порученной Вам задачи @else отданного Вами поручения @endif №{{ $task->id }}</h1>

    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">


            <div class="card">

                <div class="card-header">
                    <h2>#{{ $task->id }} {{ $task->title }}</h2>
                </div>

                <div class="card-body">


                    
                    <div class="card-title">

                        {{-- Status --}}
                        Статус:
                        @modalSelect([
                            'id' => $task->id,
                            'item' => $task->getStatus,
                            'options' => $tasksstatuses,
                            'action' => route('tasks.update', ['task' => $task]),
                            'select_name' => 'tasksstatus_id',
                        ])


                        {{-- Priority --}}
                        <br>
                        Приоритет:
                        @if ( empty($directive) )
                            <span class="text-{{ $task->getPriority->style ?? 'primary' }}">{{ $task->getPriority->display_name }}</span>
                        @else
                            @modalSelect([
                                'id' => $task->id,
                                'item' => $task->getPriority,
                                'options' => $taskspriorities,
                                'action' => route('tasks.update', ['task' => $task]),
                                'select_name' => 'taskspriority_id',
                            ])
                        @endif

                    </div>


                    {{-- description --}}
                    <div class="card-text description">
                        {{-- <h4>Описание</h4> --}}
                        <p>
                            {!! $task->description !!}
                        </p>
                    </div>



                    {{-- comment_slave --}}
                    Комментарий исполнителя:
                    @if ( empty($directive) )
                        @modalTextarea([
                            'id' => $task->id,
                            'textarea_name' => 'comment_slave',
                            'textarea_display_name' => 'комментарий исполнителя',
                            'value' => $task->comment_slave,
                            'empty_value' => 'отсутствует',
                            'action' => route('tasks.update', ['task' => $task]),
                        ])
                    @else
                        {{-- {{ str_limit($task->comment_slave ?? 'отсутствует', 30) }} --}}
                        {{ $task->comment_slave ?? 'отсутствует' }}
                    @endif


                    {{-- Master/Slave --}}
                    <div class="card-title">

                        Master: {{ $task->getMaster->name }}
                        <br>
                        Slave: {{ $task->getSlave->name }}

                    </div>


                    {{-- created/updated --}}
                    <div class="card-footer text-muted bg_white">

                        Task created: {{ $task->created_at }}

                        @if( $task->created_at != $task->updated_at )
                            <br>
                            Task updated: {{ $task->updated_at }}
                        @endif

                    </div>

                    {{-- <a href="#" class="btn btn-primary form-control">Изменить</a> --}}

                </div>
            </div>

            <div class="row m-3">
                @if ( empty($directive) )
                    <a href="{{ route('tasks.index') }}">к списку задач</a>
                @else
                    <a href="{{ route('directives.index') }}">к списку поручений</a>
                @endif
            </div>

        </div>

    </div>

@endsection
