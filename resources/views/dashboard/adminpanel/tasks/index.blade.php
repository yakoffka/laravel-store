@extends('layouts.theme_switch')


@section('title', empty($directive) ? __('__list_of_tasks') : __('__list_of_directives'))


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( empty($directive) )
                {{ Breadcrumbs::render('tasks.index',  auth()->user() ) }}
            @else
                {{ Breadcrumbs::render('directives.index',  auth()->user() ) }}
            @endif
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ empty($directive) ? __('__list_of_tasks') : __('__list_of_directives') }} ({{ $tasks->total() }})</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

           <div class="row">


                @if ( empty($directive) )
                @else
                    {{-- add new directive --}}
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary form-control pb-1">{{ __('create_new_task') }}</a>
                    <div class="row col-sm-12 pb-2"></div>
                    {{-- /add new directive --}}
                @endif

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>{{ __('__id') }}</th>
                        <th>{{ __('__name') }}</th>
                        <th>{{ __('__status') }}</th>
                        <th>{{ __('__priority') }}</th>
                        <th>@if( empty($directive) ) {{ __('__master') }} @else {{ __('__slave') }} @endif</th>
                        <th class="actions2">{{ __('__actions') }}</th>
                    </tr>

                    @foreach($tasks as $task)
                        <tr class="{{ $task->getStatus->class }}">
                            <td>{{ $task->id }}</td>
                            <td class="ta_l">{{ $task->name }}</td>

                            {{-- status --}}
                            <td class="ta_l">
                                @modalSelect([
                                    'id' => $task->id,
                                    'item' => $task->getStatus,
                                    'options' => $tasksstatuses,
                                    'action' => route('tasks.update', ['task' => $task]),
                                    'select_name' => 'tasksstatus_id',
                                ])
                            </td>

                            {{-- priority --}}
                            <td class="ta_l">
                                @if ( empty($directive) )
                                    <span class="{{ $task->getPriority->class ?? 'primary' }} nowrap">{{ $task->getPriority->display_name }}</span>
                                @else
                                    @modalSelect([
                                        'id' => $task->id,
                                        'item' => $task->getPriority,
                                        'options' => $taskspriorities,
                                        'action' => route('tasks.update', ['task' => $task]),
                                        'select_name' => 'taskspriority_id',
                                    ])
                                @endif
                            </td>

                            <td>{{ empty($directive) ? $task->getMaster->name : $task->getSlave->name }}</td>

                            {{-- actions --}}
                            <td>
                                {{-- delete task --}}
                                @if ( empty($directive) )
                                @else
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger align-self-center',
                                        'cssId' => 'delete_',
                                        'item' => $task,
                                        'type_item' => 'задачу',
                                        'action' => route('tasks.destroy', $task),
                                    ])
                                @endif

                                {{-- view task --}}
                                @if ( empty($directive) )
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('directives.show', $task) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </table>


                {{-- pagination block --}}
                @if($tasks->links())
                    <div class="row col-sm-12 pagination">{{ $tasks->links() }}</div>
                @endif
                {{-- @if($tasks->appends($appends)->links())
                    <div class="row col-sm-12 pagination">{{ $tasks->links() }}</div>
                @endif --}}

            </div>
        </div>

    </div>{{-- <div class="row"> --}}

@endsection
