@extends('layouts.app')


@section('title', 'tasks')


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


    <h1>
        Список 
        @if ( empty($directive) )
            задач для Вас 
        @else
            отданных Вами поручений 
        @endif
        ( {{ $tasks->total() }} )
    </h1>


    <div class="row">
           
            
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

           <div class="row">


                {{-- <h2>(добавить отображение в виде карточек)</h2> --}}
                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>#</th>
                        <th>title task</th>
                        {{-- <th>description</th> --}}
                        <th>status</th>
                        <th>priority</th>
                        {{-- <th>created</th>
                        <th>updated</th> --}}
                        {{-- <th>date/time</th> --}}
                        <th>@if( empty($directive) ) master @else кому @endif</th>
                        {{-- <th class="actions3">actions</th> --}}
                        <th class="actions2">actions</th>
                        {{-- <th>comment_slave</th> --}}
                    </tr>

                    @foreach($tasks as $task)
                        <tr class="{{ $task->getStatus->class }}">
                            <td>{{ $task->id }}</td>
                            {{-- <td>{{ $task->yyyy }}</td> --}}
                            <td class="ta_l">{{ $task->title }}</td>
                            {{-- <td>{{ str_limit($task->description, 50) }}</td> --}}

                            {{-- description --}}
                            {{-- <td>
                                @modalMessage([
                                'cssId' => 'description_' . $task->id,
                                'title' => 'Описание задачи №' . $task->id,
                                // 'title' => str_limit($task->description ?? '-', 30),
                                'message' => $task->description,
                                ])
                            </td> --}}

                            {{-- status --}}
                            <td class="ta_l">
                                {{-- @if (
                                    auth()->user()->can('edit_tasks')
                                    or auth()->user()->id == $task->getMaster->id
                                )
                                    @modalSelect([
                                        'options' => $tasksstatuses, 
                                        'item' => $task->getStatus, 
                                        'action' => route('tasks.update', ['task' => $task]),
                                        'select_name' => 'status',
                                    ])
                                @else
                                    <button type="button" class="btn btn-{{ $task->getStatus->style ?? 'primary' }} form-control">
                                        {{ $task->getStatus->title }}
                                    </button>
                                @endif --}}
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
                                {{-- @if (
                                    empty($directive)
                                    and (
                                        auth()->user()->can('edit_tasks')
                                        or auth()->user()->id == $task->getMaster->id
                                    )
                                ) --}}
                                @if ( empty($directive) )
                                    <span class="{{ $task->getPriority->class ?? 'primary' }}">{{ $task->getPriority->display_name }}</span>
                                    {{-- <button type="button" class="btn btn-{{ $task->getPriority->style ?? 'primary' }} form-control">
                                        {{ $task->getPriority->title }}
                                    </button> --}}
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

                            {{-- <td>{{ $task->created_at }}</td>
                            <td>{{ $task->updated_at }}</td> --}}
                            {{-- <td>{{ $task->created_at }}<br>{{ $task->updated_at }}</td> --}}

                            <td>
                                @if ( empty($directive) )
                                    @if ($task->getMaster->id == auth()->user()->id)
                                        я
                                    @else
                                        {{ $task->getMaster->name }}
                                    @endif
                                @else
                                    @if ($task->getSlave->id == auth()->user()->id)
                                        себе
                                    @else
                                        {{ $task->getSlave->name }}'у
                                    @endif
                                @endif
                            </td>

                            {{-- actions --}}
                            <td>

                                {{-- delete task --}}
                                {{-- @if (
                                    auth()->user()->can('delete_tasks')
                                    or auth()->user()->id == $task->master_user_id
                                ) --}}
                                @if ( empty($directive) )
                                @else

                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger align-self-center',
                                        'cssId' => 'delele_',
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

                            {{-- comment_slave --}}
                            <!--td>

                            @if ( empty($directive) )
                            @else
                            @endif

                                @if (
                                    auth()->user()->can('edit_tasks')
                                    or auth()->user()->id == $task->slave_user_id
                                )
                                    @modalForm([
                                        'item' => $task,
                                        'button_class' => 'primary align-self-center',
                                        'modalCssId' => 'change_task_comment_' . $task->id,
                                        'modal_title' => 'Комментарий исполнителя',
                                        'text_button' => str_limit($task->comment_slave ?? '-', 30),
                                        'describe' => '',
                                        'action' => route('tasks.update', ['task' => $task]),
                                        // 'multipart' => ' enctype="multipart/form-data"',
                                        'multipart' => '',
                                        // 'method' => 'POST',
                                        'method' => 'PATCH',
                                        // 'method' => 'DELETE',
                                        'submit_text' => 'применить',
                                        'status' => true,
                                        'comment_slave' => true,
                                    ])
                                @else
                                    {{ str_limit($task->comment_slave ?? '-', 30) }}
                                @endif
                                
                            </td-->
                        </tr>
                    @endforeach

                </table>


                @if ( empty($directive) )
                @else
                    {{-- add new directive --}}
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary form-control pb-1">Поставить новую задачу</a>
                    <div class="row col-sm-12 pb-2"></div>
                    {{-- /add new directive --}}
                @endif


                <!-- pagination block -->
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
