<?php

namespace App\Http\Controllers;

use App\{Task, Taskspriority, Tasksstatus, User};
use Illuminate\Http\Request;
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
        $tasks = Task::where('slave_user_id', auth()->user()->id)
            ->orderBy('tasksstatus_id')
            ->paginate();
        $tasksstatuses = Tasksstatus::all();
        return view('dashboard.adminpanel.tasks.index', compact('tasks', 'tasksstatuses'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function directives()
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );

        $directive = true;
        $taskspriorities = Taskspriority::all();
        $tasks = Task::where('master_user_id', auth()->user()->id)
            ->orderBy('tasksstatus_id')
            ->paginate();
        $tasksstatuses = Tasksstatus::all();

        return view('dashboard.adminpanel.tasks.index', compact('directive', 'tasks', 'taskspriorities', 'tasksstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function create(Task $task)
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );
        $slaves = User::where('id', '>', 1)->get();
        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();
        return view('dashboard.adminpanel.tasks.create', compact('slaves', 'taskspriorities', 'tasksstatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function store(Task $task)
    {
        abort_if ( auth()->user()->cannot('create_tasks'), 403 );

        request()->validate([
            'slave_user_id' => 'required|integer|exists:users,id',
            'name' => 'string',
            'description' => 'required|string',
            'taskspriority_id' => 'exists:taskspriorities,id',
        ]);


        if ( !$task = Task::create([
            'master_user_id' => auth()->user()->id,
            'slave_user_id' => request('slave_user_id'),
            'name' => request('name') ?? Str::limit(request('description'), 20),
            'description' => request('description'),
            'tasksstatus_id' => 1, // TODO привязка к id
            'taskspriority_id' => request('taskspriority_id'),
        ])) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

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
                $task->master_user_id !== auth()->user()->id
                and
                $task->slave_user_id !== auth()->user()->id // ???
            ),
        403 );

        // $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();

        return view('dashboard.adminpanel.tasks.show', compact('task', /*'taskspriorities', */'tasksstatuses'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function directive(Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('view_tasks') 
            and 
            (
                $task->master_user_id !== auth()->user()->id
                or
                $task->slave_user_id !== auth()->user()->id // ???
            ),
        403 );

        $directive = true;
        $taskspriorities = Taskspriority::all();
        $tasksstatuses = Tasksstatus::all();

        return view('dashboard.adminpanel.tasks.show', compact('directive', 'task', 'taskspriorities', 'tasksstatuses'));
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
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('edit_tasks') 
            and 
            (
                $task->master_user_id !== auth()->user()->id
                and
                $task->slave_user_id !== auth()->user()->id
            ),
        403 );

        request()->validate([
            'tasksstatus_id' => 'exists:tasksstatuses,id',
            'taskspriority_id' => 'exists:taskspriorities,id',            
            'comment_slave' => 'string|nullable',
        ]);

        // закрыть задачу может только тот, кто её открыл
        if ( 
            !is_null( request('tasksstatus_id') ) 
            and request('tasksstatus_id') === '4' // TODO!!! привязка к id!!!
            and auth()->user()->id !== $task->master_user_id
        ) {
            return back()->withErrors(['закрыть задачу может только тот, кто её открыл'])->withInput();
        }

        // комментировать задачу может только исполнитель
        if ( 
            !is_null( request('comment_slave') ) 
            and (
                auth()->user()->id !== $task->slave_user_id
                and
                auth()->user()->cannot('edit_tasks')
            )
        ) {
            return back()->withErrors(['комментировать задачу может только исполнитель'])->withInput();
        }

        if (!$task->update([
            'tasksstatus_id' => request('tasksstatus_id') ?? $task->tasksstatus_id,          // изменить! перезаписать только при изменении!
            'taskspriority_id' => request('taskspriority_id') ?? $task->taskspriority_id,    // изменить! перезаписать только при изменении!
            'comment_slave' => request('comment_slave') ?? $task->comment_slave,             // изменить! перезаписать только при изменении!
        ])) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        // $message = 'Task #' . $task->id . ' "' . $task->name . '" is edit';

        // session()->flash('message', $message);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        abort_if ( 
            auth()->user()->cannot('delete_tasks') 
            and 
            (
                $task->master_user_id !== auth()->user()->id
                or
                $task->slave_user_id !== auth()->user()->id // ???
            ),
        403 );

        $message = 'Task #' . $task->id . ' "' . $task->name . '" is deleted';

        if ( !$task->delete() ) {
            return back()->withErrors(['something wrong! ERR #' . __line__]);
        }

        session()->flash('message', $message);

        return back();
    }


    // удаленные записи
    // $flight->trashed();

}
