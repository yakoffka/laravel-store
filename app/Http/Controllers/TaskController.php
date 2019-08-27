<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;

class TaskController extends Controller
{
    // добавить делегирование и переназначение (другому исполнителю)
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('slave_user_id', auth()->user()->id)->paginate();
        // $directives = Task::where('master_user_id', auth()->user()->id)->paginate();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function directives()
    {
        $tasks = Task::where('master_user_id', auth()->user()->id)->paginate();
        return view('tasks.directives', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Task $task)
    {
        // $tasks = Task::where($task->id)->paginate();
        // return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );

        // array_column(config('task.statuses'), 'name')
        $this->validate($request, [
            'master_user_id' => 'required|integer|exists:users,id',
            'slave_user_id' => 'required|integer|exists:users,id',
            'title' => 'string',
            'description' => 'required|string',
            'status' => 'required|string|',
                [
                    'required',
                    Rule::in(array_column(config('task.statuses'), 'name')),
                ],
            'priority' => 'required|string|',
                [
                    'required',
                    Rule::in(array_column(config('task.priorities'), 'name')),
                ],
        ]);


        // уточнить ассоциацию присваивания (правая или левая)
        if ( $task = Task::create([
            'master_user_id' => $request->master_user_id,
            'slave_user_id' => $request->slave_user_id,
            'title' => $request->title ?? Str::limit($request->description, 20),
            'slug' => Str::slug($request->title, '-'),
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
        ])) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        $message = 'Task #' . $task->id . ' "' . $task->title . '" is create successfull';

        session()->flash('message', $message);

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $tasks = Task::where($task->id)->paginate();
        return view('tasks.show', compact('tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('edit_tasks') 
            and 
            (
                $task->master_user_id != auth()->user()->id
                or
                $task->slave_user_id != auth()->user()->id
            ),
        403 );


        $this->validate($request, [
            // 'value' => 'required|string',
            // 'master_user_id' => 'required|integer|exists:users,id',
            // 'slave_user_id' => 'required|integer|exists:users,id',
            // 'title' => 'string',
            // 'description' => 'required|string',
            // 'status' => 'required|string|',
            //     [
            //         'required',
            //         Rule::in(array_column(config('task.statuses'), 'name')),
            //     ],
            'status' => [
                Rule::in(array_column(config('task.statuses'), 'name')),
            ],
            // 'priority' => 'required|string|',
            //     [
            //         'required',
            //         Rule::in(array_column(config('task.priorities'), 'name')),
            //     ],
            'comment_slave' => 'string',
        ]);
        // dd('hello, my frend!');


        // закрыть задачу может только тот, кто её открыл
        if ( 
            !is_null( $request->status ) 
            and $request->status == 'closed'
            and auth()->user()->id !== $task->master_user_id
        ) {
            return back()->withErrors(['закрыть задачу может только тот, кто её открыл'])->withInput();
        }


        // комментировать задачу может только исполнитель
        if ( 
            !is_null( $request->comment_slave ) 
            and auth()->user()->id !== $task->slave_user_id
        ) {
            return back()->withErrors(['комментировать задачу может только исполнитель'])->withInput();
        }

        // if ( is_null( $request->status ) ) {
        //     dd('hello, my frend! is_null!');
        // } else {
        //     dd('goodby, my frend! is_not_null!');            
        // }
        // if ( is_null( $request->status ) ) {
        //     dd('hello, my frend! is_null!');
        // } else {
        //     dd('goodby, my frend! is_not_null! i am "' . $request->status . '"');            
        // }


        if (!$task->update([
            'status' => $request->status ?? $task->status,
            'comment_slave' => $request->comment_slave ?? $task->comment_slave,
        ])) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        $message = 'Task #' . $task->id . ' "' . $task->title . '" is edit';

        session()->flash('message', $message);

        // return redirect()->route('tasks.show', $task);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('delete_tasks') 
            and 
            (
                $task->master_user_id != auth()->user()->id
                or
                $task->slave_user_id != auth()->user()->id
            ),
        403 );

        $message = 'Task #' . $task->id . ' "' . $task->title . '" is deleted';

        if ( !$task->delete() ) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        session()->flash('message', $message);

        return redirect()->route('tasks.index');
    }


    // удаленные записи
    // $flight->trashed();
}
