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
                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>#</th>
                        <th>title</th>
                        <th>description</th>
                        <th>status</th>
                        <th>priority</th>
                        <th>created</th>
                        <th>updated</th>
                        <th>master</th>
                        <th class="actions3">actions</th>
                        <th>comment_slave</th>
                    </tr>

                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            {{-- <td>{{ $task->yyyy }}</td> --}}
                            <td>{{ $task->title }}</td>
                            {{-- <td>{{ str_limit($task->description, 50) }}</td> --}}
                            <td>@modalMessage([
                                    'cssId' => 'description_' . $task->id,
                                    'title' => 'Описание задачи №' . $task->id,
                                    'message' => $task->description,
                                    ])</td>
                            {{-- <td>{{ $task->status }}</td> --}}
                            <td>
                                {{-- if --}}
                                    @modalSelect([
                                        'options' => array_column(config('task.statuses'), 'name'), 
                                        'item' => $task, 
                                        'action' => route('tasks.update', ['task' => $task]),
                                        'select_name' => 'status',
                                        'btn_class' => config('task.statuses')[$task->status]['style'],
                                    ])
            

                            </td>
                            {{-- <td>{{ $task->priority }}</td> --}}
                            <td>
                                {{-- if --}}
                                    @modalSelect([
                                        'options' => array_column(config('task.priorities'), 'name'), 
                                        'item' => $task, 
                                        'action' => route('tasks.update', ['task' => $task]),
                                        'select_name' => 'priority',
                                        'btn_class' => config('task.priorities')[$task->priority]['style'],
                                    ])
        

                            </td>
                            <td>{{ $task->created_at }}</td>
                            <td>{{ $task->updated_at }}</td>
                            <td>
                                @if ($task->getMaster->id == auth()->user()->id)
                                    self
                                @else
                                    {{ $task->getMaster->name }}
                                @endif
                            </td>
                            <td class="row align-items-center justify-content-center">

                                {{-- delete task --}}
                                @if ( auth()->user()->can('delete_tasks') or auth()->user()->id == $task->master_user_id )
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger align-self-center',
                                        'cssId' => 'delele_',
                                        'item' => $task,
                                        'type_item' => 'задачу',
                                        'action' => route('tasks.destroy', $task), 
                                    ])
                                @endif

                                {{-- change comment --}}
                                @if ( auth()->user()->can('edit_tasks') or auth()->user()->id == $task->slave_user_id )
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
                                @endif
                            </td>
                            <td>
                                {{-- @modalForm([
                                    'cssId' => 'change_task_comment_' . $task->id,
                                    'text_button' => $task->comment_slave,
                                    'title' => 'change items',
                                    'qty' => $cart->items[$i]['qty'],
                                    'product' => $item['item'],
                                ]) --}}
                                {{ $task->comment_slave ?? '-' }}
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
