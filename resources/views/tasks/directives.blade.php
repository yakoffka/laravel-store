@extends('layouts.app')


@section('title', 'directives')


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 p-0 breadcrumbs">
            {{ Breadcrumbs::render('directives.index', auth()->user() ) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 p-0 searchform">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>My directives</h1>


    <div class="row">
           
            
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 pr-0">
           <div class="row">
                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>#</th>
                        <th>id</th>
                        <th>title</th>
                        <th>description</th>
                        <th>status</th>
                        <th>priority</th>
                        <th>created</th>
                        <th>updated</th>
                        <th>slave</th>
                        <th class="actions3">actions</th>
                    </tr>

                    @foreach($tasks as $i => $task)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $task->id }}</td>
                            {{-- <td>{{ $task->yyyy }}</td> --}}
                            <td>{{ $task->title }}</td>
                            {{-- <td>{{ str_limit($task->description, 50) }}</td> --}}
                            <td>@modalMessage([
                                    'cssId' => 'description_' . $task->id,
                                    'title' => 'Описание задачи №' . $task->id,
                                    'message' => $task->description,
                                    ])</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td>{{ $task->updated_at }}</td>
                            <td>{{ $task->getSlave->name }}</td>
                            <td>-</td>
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
