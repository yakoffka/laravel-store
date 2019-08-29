<?php

namespace App\Http\Controllers;

use App\{Task, Taskspriority, Tasksstatus, User};
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
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
        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();
        return view('tasks.index', compact('tasks', 'taskspriorities', 'tasksstatuses'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function directives()
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );

        $tasks = Task::where('master_user_id', auth()->user()->id)->paginate();
        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();
        return view('tasks.directives', compact('tasks', 'taskspriorities', 'tasksstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Task $task)
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );
        $slaves = User::all();
        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();
        return view('tasks.create', compact('slaves', 'taskspriorities', 'tasksstatuses'));
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
            // 'master_user_id' => 'required_unless:slave_user_id,integer|integer|exists:users,id',
            // 'slave_user_id' => 'required_unless:master_user_id,integer|integer|exists:users,id',
            'slave_user_id' => 'required|integer|exists:users,id',
            'title' => 'string',
            'description' => 'required|string',
            // 'status' => 'required|string|',
            // [
            //     'required',
            //     Rule::in(array_column(config('task.statuses'), 'name')),
            // ],
            // 'priority' => 'required|string|',
            // [
            //     'required',
            //     Rule::in(array_column(config('task.priorities'), 'name')),
            // ],
            // 'tasksstatus_id' => 'exists:tasksstatuses,id',
            'taskspriority_id' => 'exists:taskspriorities,id',
        ]);


        // уточнить ассоциацию присваивания (правая или левая)
        if ( !$task = Task::create([
            'master_user_id' => auth()->user()->id,
            'slave_user_id' => $request->slave_user_id,
            'title' => $request->title ?? Str::limit($request->description, 20),
            'slug' => Str::slug($request->title ?? substr($request->title, 0, 20), '-'), // TODO unic!!!
            'description' => $request->description,
            'tasksstatus_id' => 1, // TODO привязка к id
            'taskspriority_id' => $request->taskspriority_id,
        ])) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }
        // dd('dddd');

        $message = 'Task #' . $task->id . ' "' . $task->title . '" is create successfull';

        session()->flash('message', $message);

        return redirect()->route('directives.index', auth()->user());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('view_tasks') 
            and 
            (
                $task->master_user_id != auth()->user()->id
                or
                $task->slave_user_id != auth()->user()->id // ???
            ),
        403 );

        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();

        return view('tasks.show', compact('task', 'taskspriorities', 'tasksstatuses'));
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
        // dd( $task->master_user_id, $task->slave_user_id, auth()->user()->id );
        abort_if ( 
            auth()->user()->cannot('edit_tasks') 
            and 
            (
                $task->master_user_id != auth()->user()->id
                and
                $task->slave_user_id != auth()->user()->id
            ),
        403 );


        $this->validate($request, [

            // 'status' => [
            //     Rule::in(array_column(config('task.statuses'), 'name')),
            // ],
            // 'priority' => 'required|string|',
            // [
            //     'required',
            //     Rule::in(array_column(config('task.priorities'), 'name')),
            // ],

            'tasksstatus_id' => 'exists:tasksstatuses,id',
            'taskspriority_id' => 'exists:taskspriorities,id',
            
            // 'tasksstatus_id' => 'exists:tasksstatuses',
            // 'taskspriority_id' => 'exists:taskspriorities',
            
            'comment_slave' => 'string',
        ]);
        // dd('hello, my frend!');

        // dd($request->tasksstatus_id, $request->taskspriority_id, $request->comment_slave);

        // закрыть задачу может только тот, кто её открыл
        if ( 
            !is_null( $request->tasksstatus_id ) 
            and $request->tasksstatus_id == '4' // TODO!!! привязка к id!!!
            and auth()->user()->id !== $task->master_user_id
        ) {
            return back()->withErrors(['закрыть задачу может только тот, кто её открыл'])->withInput();
        }


        // комментировать задачу может только исполнитель
        if ( 
            !is_null( $request->comment_slave ) 
            and (
                auth()->user()->id !== $task->slave_user_id
                and
                auth()->user()->cannot('edit_tasks')
            )
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
            'tasksstatus_id' => $request->tasksstatus_id ?? $task->tasksstatus_id,          // изменить! перезаписать только при изменении!
            'taskspriority_id' => $request->taskspriority_id ?? $task->taskspriority_id,    // изменить! перезаписать только при изменении!
            'comment_slave' => $request->comment_slave ?? $task->comment_slave,             // изменить! перезаписать только при изменении!
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
                $task->slave_user_id != auth()->user()->id // ???
            ),
        403 );

        $message = 'Task #' . $task->id . ' "' . $task->title . '" is deleted';

        if ( !$task->delete() ) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        session()->flash('message', $message);

        return back();
    }


    // удаленные записи
    // $flight->trashed();

}
