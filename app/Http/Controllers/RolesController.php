<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\{Role, Action};
use App\Permission;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
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
        abort_if ( auth()->user()->cannot('view_roles'), 403 );
        $roles = Role::all();
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( auth()->user()->cannot('create_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.create', compact('permissions'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Role $role)
    {
        abort_if ( auth()->user()->cannot('create_roles'), 403 );

        $arrToValidate['name'] = 'required|string|max:255|unique:roles';
        $arrToValidate['display_name'] = 'required|string|max:255|unique:roles';
        $arrToValidate['description'] = 'required|string|max:255';
        

        $permissions = Permission::all()->toArray();
        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'string|max:3';
        }

        $validator = request()->validate($arrToValidate);

        // dd(request()->all());
        // dd(request('rank'));
        $role = Role::create([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
            'added_by_user_id' => auth()->user()->id,
        ]);

        
        if ($role) {
            $mess = 'Роль ' . $role->name . ' создана успешно.';
        } else {
            return back()->withErrors(['error #' . __line__ ])->withInput();
        }
        

        // attach permissions
        if ( auth()->user()->can('edit_roles') ) {
            $attach_roles = [];

            foreach ( $permissions as $permission ) {
                if (
                    request($permission['name']) == 'on' 
                    and !$role->perms->contains('name', $permission['name']) 
                    and auth()->user()->can($permission['name']) 
                ) {
                    $role->attachPermission($permission['id']);
                    $attach_roles[] = $permission['name'];
                }
            }

            $mess .= $attach_roles ? ' Роли присвоены разрешения (' . count($attach_roles) . '): ' . implode(', ', $attach_roles) . '.' : '';
        }

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'role',
            'type_id' => $role->id,
            'action' => 'create',
            'description' => 
                'Создание роли ' 
                . $role->name
                . '. Исполнитель: ' 
                . auth()->user()->name
                . '. '
                . $mess
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', $mess);

        return redirect()->route('roles.show', compact('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        abort_if ( auth()->user()->cannot('view_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        abort_if ( auth()->user()->cannot('edit_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role)
    {
        abort_if ( auth()->user()->cannot('edit_roles'), 403 );

        $arrToValidate['name'] = 'required|string|max:255'; // |unique:roles
        $arrToValidate['display_name'] = 'required|string|max:255'; // |unique:roles
        $arrToValidate['description'] = 'required|string|max:255';

        $permissions = Permission::all()->toArray();

        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'string|max:3';
        }

        $validator = request()->validate($arrToValidate);

        $update = $role->update([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
            'edited_by_user_id' => auth()->user()->id,
        ]);

        
        if ($update) {
            $mess = 'Роль ' . $role->name . ' успешно обновлена.';
        } else {
            return back()->withErrors(['error #' . __line__ ])->withInput();
        }


        if ( auth()->user()->can('edit_roles') ) {
            $attach_roles = $take_roles = [];
            foreach ( $permissions as $permission ) {

                // attach Permission
                if ( request($permission['name']) == 'on' and !$role->perms->contains('name', $permission['name']) and auth()->user()->can($permission['name']) ) {
                    $role->attachPermission($permission['id']);

                    $attach_roles[] = $permission['name'];
                    
                // take Permission
                } elseif ( empty(request($permission['name'])) and $role->perms->contains('name', $permission['name']) and auth()->user()->can($permission['name']) ) {
                    $take_role = DB::table('permission_role')->where([
                        ['permission_id', '=', $permission['id']],
                        ['role_id', '=', $role->id],
                    ])->delete();

                    $take_roles[] = $permission['name'];
                }
            }
            $mess .= ($attach_roles ? ' Добавлены разрешения (' . count($attach_roles) . '): ' . implode(', ', $attach_roles) . '.' : '') . ($take_roles ? ' Удалены разрешения (' . count($take_roles) . '): ' . implode(', ', $take_roles) . '.' : '');
        }

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'role',
            'type_id' => $role->id,
            'action' => 'update',
            'description' => 
                'Редактирование роли ' 
                . $role->name
                . '. Исполнитель: ' 
                . auth()->user()->name
                . '. '
                . $mess
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', $mess);

        return redirect()->route('roles.show', compact('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        abort_if ( auth()->user()->cannot('delete_roles'), 403 );

        if ( $role->id < 5  ) {
            return back()->withErrors(['"' . $role->name . '" is basic role and can not be removed.']);
        }

        if ($role->users->count()) {
            return back()->withErrors(['"' . $role->name . '" role is assigned to ' . $role->users->count() . ' users. before removing it is necessary to take it away.']);
        }
        $role->forceDelete();
        // $role->delete();
        return redirect()->route('roles.index');
    }
}
