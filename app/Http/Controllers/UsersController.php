<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;

class UsersController extends Controller
{
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
        abort_if ( Auth::user()->cannot('view_users'), 403 );
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if ( Auth::user()->cannot('view_users'), 403 );
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if ( !Auth::user()->can('edit_users'), 403 );
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
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
        abort_if ( !Auth::user()->can('edit_users'), 403 );

        $validator = Validator::make (request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', // |unique:users
            'password' => 'required|string|min:6|max:255', // confirmed?
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ( !Hash::check(request('password'),$user->password )) {
            return back()->withErrors(['failed password'])->withInput();
        }

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
        ]);

        return redirect( route('usersShow', ['user' => $user]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if ( !Auth::user()->can('edit_users'), 403 );
        $user->delete();
        return redirect( route('users'));
    }

}
