@extends('layouts.theme_switch')

@section( 'title', empty($directive) ? __('task_show', ['id' => $task->id]) : __('directive_show', ['id' => $task->id]))

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

    <h1>{{ empty($directive) ? __('task_show', ['id' => $task->id]) : __('directive_show', ['id' => $task->id]) }}</h1>

    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <div class="card">

                <div class="card-header">
                    <h2>#{{ $task->id }} {{ $task->name }}</h2>
                </div>

                <div class="card-body">

                    <div class="card-title">
                        {{-- Status --}}
                        {{ __('__status') }}:
                        @modalSelect([
                            'id' => $task->id,
                            'item' => $task->getStatus,
                            'options' => $tasksstatuses,
                            'action' => route('tasks.update', ['task' => $task]),
                            'select_name' => 'tasksstatus_id',
                        ])
                        {{-- Priority --}}
                        <br>
                        {{ __('__priority') }}:
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
                        {{-- <h4>{{ __('__Description') }}</h4> --}}
                        <p>
                            {!! $task->description !!}
                        </p>
                    </div>

                    {{-- comment_slave --}}
                    {{ __('__comm_slave') }}:
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
                        {{ __('__master') }}: {{ $task->getMaster->name }}
                        <br>
                        {{ __('__slave') }}: {{ $task->getSlave->name }}
                    </div>

                    {{-- created/updated --}}
                    <div class="card-footer text-muted bg_white">
                        {{ __('created_at') }}: {{ $task->created_at }}
                        @if( $task->created_at != $task->updated_at )
                            <br>
                            {{ __('updated_at') }}: {{ $task->updated_at }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="row m-3">
                @if ( empty($directive) )
                    <a href="{{ route('tasks.index') }}">{{ __('to_tasks_index') }}</a>
                @else
                    <a href="{{ route('directives.index') }}">{{ __('to_directives_index') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection
