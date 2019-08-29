@extends('layouts.app')

@section('title', 'task')

@section('content')

    <div class="row searchform_breadcrumbs">
        {{-- <div class="col col-sm-9 breadcrumbs"> --}}
        <div class="col-xs-12 col-sm-12 col-md-9 p-0 breadcrumbs">
            {{ Breadcrumbs::render('tasks.show', $task) }}
        </div>
        {{-- <div class="col col-sm-3 searchform"> --}}
        <div class="col-xs-12 col-sm-12 col-md-3 p-0 searchform">
            <div class="d-none d-md-block">@include('layouts.partials.searchform')</div>
        </div>
    </div>

    <h1>Просмотр задачи №{{ $task->id }}</h1>

    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 pr-0">


            <div class="card">

                <div class="card-header">
                    <h2>{{ $task->title }}</h2>
                </div>

                <div class="card-body">

                    <div class="card-title">Статус задачи: {{ $task->getStatus->display_name }}</div>
                    <div class="card-title">Приоритет задачи: {{ $task->getPriority->display_name }}</div>

                    {{-- <div class="row">

                        @modalSelect([
                            'id' => $task->id,
                            'item' => $task->getStatus, 
                            'options' => $tasksstatuses, 
                            'action' => route('tasks.update', ['task' => $task]),
                            'select_name' => 'tasksstatus_id',
                        ])

                        @if (
                            auth()->user()->can('edit_tasks')
                            or auth()->user()->id == $task->getMaster->id
                        )
                            @modalSelect([
                                'id' => $task->id,
                                'item' => $task->getPriority, 
                                'options' => $taskspriorities, 
                                'action' => route('tasks.update', ['task' => $task]),
                                'select_name' => 'taskspriority_id',
                            ])
                        @else
                            <button type="button" class="btn btn-{{ $task->getPriority->style ?? 'primary' }} form-control">
                                {{ $task->getPriority->title }}
                            </button>
                        @endif

                    </div> --}}

                    <h3 class="card-text">
                        {{ $task->description }}
                    </h3>
                    <div class="card-title">Master: {{ $task->getMaster->name }}</div>
                    <div class="card-title">Task created: {{ $task->created_at }}</div>
                    <div class="card-title">Task updated: {{ $task->updated_at }}</div>
                    {{-- <a href="#" class="btn btn-primary form-control">change?</a> --}}
                </div>
            </div>

            <div class="row m-3">
                <a href="{{ route('tasks.index', auth()->user()) }}">к списку задач</a>
            </div>

        </div>
        
    </div>

@endsection
