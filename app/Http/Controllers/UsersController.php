<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\{Action, Role, Permission,User};

class UsersController extends Controller
{
    public function __construct() {
        // $this->middleware(['auth', 'permission:view_users']);
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( Auth::user()->cannot('view_users'), 403 );
        $users = User::all();
        $permissions = Permission::all();
        // $actions = Action::all();
        $actions = Action::all()->sortByDesc('created_at')->slice(0, config('custom.num_last_actions'));// last 50!

        return view('users.index', compact('users', 'permissions', 'actions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort_if ( Auth::user()->cannot('view_users') and Auth::user()->id != $user->id , 403 );
        $permissions = Permission::all();
        $actions = Action::where('user_id', $user->id)->get();// last 50!

        return view('users.show', compact('user', 'permissions', 'actions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if ( !Auth::user()->can('edit_users') and Auth::user()->id !== $user->id, 403 );
        $roles = Role::get();
        $permissions = Permission::all();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        abort_if ( Auth::user()->cannot('edit_users') and Auth::user()->id !== $user->id, 403 );

        $validator = Validator::make (request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', // |unique:users
            'role' => 'nullable|integer|max:255',
            'take_role' => 'nullable|integer|max:255',
            'password' => 'nullable|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ( ( request('role' ) or request( 'take_role' ) ) and Auth::user()->cannot('edit_roles') ) {
            return back()->withErrors('you can not attach and take roles!')->withInput();
        }


        if ( Auth::user()->can('edit_users') ) {

            // update user without input password
            $user->update([
                'name' => request('name'),
                'email' => request('email'),
            ]);

            // attach Role
            if ( request('role' ) ) {
                // !! проверить на уникальность! SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '5-2' for key 'PRIMARY' (SQL: insert into `role_user` (`role_id`, `user_id`) values (2, 5))
                $user->attachRole(request('role'));
            }

            // take Role
            if ( request( 'take_role' ) ) {
                // dont delete last role!
                if ( count(DB::table('role_user')->where('user_id', '=', $user->id)->get()) < 2 ) {
                    return back()->withErrors(['You can not take the last role!']);
                }

                $take_role = DB::table('role_user')->where([
                    ['user_id', '=', $user->id],
                    ['role_id', '=', request('take_role')],
                ])->delete();
            }

        } elseif ( Auth::user()->id === $user->id ) {
            if ( !Hash::check(request('password'),$user->password )) {
                return back()->withErrors(['failed password'])->withInput();
            }
    
            // $user->update([
            //     'name' => request('name'),
            //     'email' => request('email'),
            // ]);
            if ( !$user->update([
                    'name' => request('name'),
                    'email' => request('email'),
            ]) ) {
                return back()->withError(['something wrong. err' . __line__]);
            }
            
        } else {
            abort(403, 'Unauthorized action.');
        }

        // add session flash!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'user',
            'type_id' => $user->id,
            'action' => 'edit',
            'description' => 
                'Редактирование пользователя ' 
                . $user->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', 'User "' . $user->name . '" with id=' . $user->id . ' was successfully edited.');

        return redirect( route('users.show', ['user' => $user]));
        // return redirect( route('users.index') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $subordination = $this->subordination($user);
        // abort_if ( !Auth::user()->can('delete_users') or !$subordination, 403 );
        abort_if ( !Auth::user()->can('delete_users'), 403 );

        // dont destroy last owner!
        if ( $user->roles->first()->id === 1 and DB::table('role_user')->where('role_id', '=', 1)->get()->count() === 1 ) {
            return back()->withErrors([$user->name . ' is last owner. dont destroy him!']);
        }

        // $user->delete();
        if ( !$user->delete() ) {
            return back()->withError(['something wrong. err' . __line__]);
        }

        // add session flash!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'user',
            'type_id' => $user->id,
            'action' => 'delete',
            'description' => 
                'Удаление пользователя ' 
                . $user->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', 'User "' . $user->name . '" with id=' . $user->id . ' was successfully deleted.');

        return redirect( route('users.index'));
    }

}
