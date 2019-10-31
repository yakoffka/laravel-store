<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\{Customevent, Role, Permission, User};

class UsersController extends Controller
{
    public function __construct() { $this->middleware('auth'); }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( Auth::user()->cannot('view_users'), 403 );
        $users = User::paginate();
        $permissions = Permission::all();

        return view('dashboard.adminpanel.users.index', compact('users', 'permissions'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort_if ( Auth::user()->cannot('view_users') and Auth::user()->id !== $user->id , 403 );
        $permissions = Permission::all();
        $customevents = Customevent::where('user_id', $user->id)->get();// last 50!

        return view('dashboard.adminpanel.users.show', compact('user', 'permissions', 'customevents'));
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

        return view('dashboard.adminpanel.users.edit', compact('user', 'roles', 'permissions'));
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

        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id.',id', // |unique:users,email,'.$user->id.',id'
            'role' => 'nullable|integer|max:255',
            'take_role' => 'nullable|integer|max:255',
            'password' => 'nullable|string|min:6|max:255',
        ]);

        if ( ( request('role' ) or request( 'take_role' ) ) and Auth::user()->cannot('edit_roles') ) {
            return back()->withErrors(__('You can not attach and take roles!'))->withInput();
        }


        // self edit
        if ( Auth::user()->id === $user->id ) {
            if ( !Hash::check(request('password'), $user->password )) {
                return back()->withErrors(['failed password'])->withInput();
            }
            $user->name = request('name');
            $user->email = request('email');

        // edit other user without input password
        } elseif ( Auth::user()->can('edit_users') ) {
            $user->name = request('name');
            $user->email = request('email');

            // attach Role
            if ( request('role' ) ) {
                $user->attachRole(request('role'));
            }

            // take Role
            if ( request( 'take_role' ) ) {
                // dont delete last role!
                if ( count(DB::table('role_user')->where('user_id', '=', $user->id)->get()) < 2 ) {
                    return back()->withErrors([__('You can not take the last role!')]);
                }

                $take_role = DB::table('role_user')->where([
                    ['user_id', '=', $user->id],
                    ['role_id', '=', request('take_role')],
                ])->delete();
            }

            if ( request('role' ) or request( 'take_role' ) ) {
                $user->touch();
            }

        } else {
            abort(403);
        }

        if ( !$user->save() ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

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
        abort_if ( !Auth::user()->can('delete_users'), 403 );

        // dont destroy last owner!
        if ( $user->roles->first()->id === 1 and DB::table('role_user')->where('role_id', '=', 1)->get()->count() === 1 ) {
            return back()->withErrors([$user->name . ' is last owner. dont destroy him!']);
        }

        // $user->delete();
        if ( !$user->delete() ) {
            return back()->withError(['something wrong. err' . __line__]);
        }

        return redirect( route('users.index'));
    }

}
