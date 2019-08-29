@extends('layouts.app')


@section('title', 'tasks')


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 p-0 breadcrumbs">
            {{ Breadcrumbs::render('tasks.index', auth()->user() ) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 p-0 searchform">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>My tasks ({{ $tasks->total() }})</h1>


    <div class="row">
           
            
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 pr-0">

           <div class="row">

            
                <h2>new list</h2>
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
                        <th>master</th>
                        {{-- <th class="actions3">actions</th> --}}
                        <th class="actions2">actions</th>
                        {{-- <th>comment_slave</th> --}}
                    </tr>

                    @foreach($tasks as $task)
                        <tr>
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
                            <td>
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
                            <td>
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
                            </td>

                            {{-- <td>{{ $task->created_at }}</td>
                            <td>{{ $task->updated_at }}</td> --}}
                            {{-- <td>{{ $task->created_at }}<br>{{ $task->updated_at }}</td> --}}

                            <td>
                                @if ($task->getMaster->id == auth()->user()->id)
                                    self
                                @else
                                    {{ $task->getMaster->name }}
                                @endif
                            </td>

                            {{-- actions --}}
                            {{-- <td class="row align-items-center justify-content-center"> --}}
                            <td>

                                {{-- delete task --}}
                                @if (
                                    auth()->user()->can('delete_tasks')
                                    or auth()->user()->id == $task->master_user_id
                                )
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger align-self-center',
                                        'cssId' => 'delele_',
                                        'item' => $task,
                                        'type_item' => 'задачу',
                                        'action' => route('tasks.destroy', $task), 
                                    ])
                                @endif

                                {{-- change comment --}}
                                {{-- @if (
                                    auth()->user()->can('edit_tasks')
                                    or auth()->user()->id == $task->slave_user_id
                                )
                                    @modalForm([
                                        'item' => $task,
                                        'button_class' => 'primary align-self-center',
                                        'modalCssId' => 'change_task_comment_' . $task->id,
                                        'modal_title' => 'Комментарий исполнителя',
                                        'text_button' => '<i class="fas fa-pen-nib"></i>',
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
                                @endif --}}

                                {{-- view task --}}
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            </td>

                            {{-- comment_slave --}}
                            <!--<td>
                                {{-- @modalForm([
                                    'cssId' => 'change_task_comment_' . $task->id,
                                    'text_button' => $task->comment_slave,
                                    'title' => 'change items',
                                    'qty' => $cart->items[$i]['qty'],
                                    'product' => $item['item'],
                                ]) --}}
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

                    {{-- 'cssId' => 'description_' . $task->id,
                    'title' => str_limit($task->description, 50),
                    // 'title' => mb_substr($order->comment, 0, 20) . '...',
                    'message' => $task->description, --}}

                </table>
               

                <h2>old list</h2>
                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>#</th>
                        <th>title task</th>
                        <th>description</th>
                        <th>status</th>
                        <th>priority</th>
                        {{-- <th>created</th>
                        <th>updated</th> --}}
                        {{-- <th>date/time</th> --}}
                        <th>master</th>
                        {{-- <th class="actions3">actions</th> --}}
                        <th class="actions2">actions</th>
                        <th>comment_slave</th>
                    </tr>

                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            {{-- <td>{{ $task->yyyy }}</td> --}}
                            <td>{{ $task->title }}</td>
                            {{-- <td>{{ str_limit($task->description, 50) }}</td> --}}

                            {{-- description --}}
                            <td>
                                @modalMessage([
                                'cssId' => 'description_' . $task->id,
                                'title' => 'Описание задачи №' . $task->id,
                                // 'title' => str_limit($task->description ?? '-', 30),
                                'message' => $task->description,
                                ])
                            </td>

                            {{-- status --}}
                            <td>
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
                            <td>
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
                            </td>

                            {{-- <td>{{ $task->created_at }}</td>
                            <td>{{ $task->updated_at }}</td> --}}
                            {{-- <td>{{ $task->created_at }}<br>{{ $task->updated_at }}</td> --}}

                            <td>
                                @if ($task->getMaster->id == auth()->user()->id)
                                    self
                                @else
                                    {{ $task->getMaster->name }}
                                @endif
                            </td>

                            {{-- actions --}}
                            {{-- <td class="row align-items-center justify-content-center"> --}}
                            <td>

                                {{-- delete task --}}
                                @if (
                                    auth()->user()->can('delete_tasks')
                                    or auth()->user()->id == $task->master_user_id
                                )
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger align-self-center',
                                        'cssId' => 'delele_',
                                        'item' => $task,
                                        'type_item' => 'задачу',
                                        'action' => route('tasks.destroy', $task), 
                                    ])
                                @endif

                                {{-- change comment --}}
                                {{-- @if (
                                    auth()->user()->can('edit_tasks')
                                    or auth()->user()->id == $task->slave_user_id
                                )
                                    @modalForm([
                                        'item' => $task,
                                        'button_class' => 'primary align-self-center',
                                        'modalCssId' => 'change_task_comment_' . $task->id,
                                        'modal_title' => 'Комментарий исполнителя',
                                        'text_button' => '<i class="fas fa-pen-nib"></i>',
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
                                @endif --}}

                                {{-- view task --}}
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            </td>

                            {{-- comment_slave --}}
                            <td>
                                {{-- @modalForm([
                                    'cssId' => 'change_task_comment_' . $task->id,
                                    'text_button' => $task->comment_slave,
                                    'title' => 'change items',
                                    'qty' => $cart->items[$i]['qty'],
                                    'product' => $item['item'],
                                ]) --}}
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
                                
                            </td>
                        </tr>
                    @endforeach

                    {{-- 'cssId' => 'description_' . $task->id,
                    'title' => str_limit($task->description, 50),
                    // 'title' => mb_substr($order->comment, 0, 20) . '...',
                    'message' => $task->description, --}}

                </table>

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
