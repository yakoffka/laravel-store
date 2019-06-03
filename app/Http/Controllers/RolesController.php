<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Role;
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
        abort_if ( Auth::user()->cannot('view_roles'), 403 );
        $roles = Role::all();
        $permissions = Permission::all()->toArray();

        $arr_all_role_permissions = array();
        foreach ($roles as $role) {
            $arr_all_role_permissions[$role->name] = $this->getArrPermissionId($role);
        }
        

        return view('roles.index', compact('roles', 'permissions', 'arr_all_role_permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( Auth::user()->cannot('create_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Role $role)
    {
        abort_if ( Auth::user()->cannot('create_roles'), 403 );

        $arrToValidate['name'] = 'required|string|max:255|unique:roles';
        $arrToValidate['display_name'] = 'required|string|max:255|unique:roles';
        $arrToValidate['description'] = 'required|string|max:255';

        $permissions = Permission::all()->toArray();
        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'string|max:3';
        }

        $validator = request()->validate($arrToValidate);

        $role = Role::create([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
        ]);

        if ( $role ) {
            foreach ( $permissions as $permission ) {
                if ( request($permission['name']) == 'on' ) {
                    $role->attachPermission($permission['id']);
                }
            }
        }

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
        abort_if ( Auth::user()->cannot('view_roles'), 403 );

        $arr_role_permissions = $this->getArrPermissionId($role);
        $permissions = Permission::all()->toArray();

        return view('roles.show', compact('role', 'permissions', 'arr_role_permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        abort_if ( Auth::user()->cannot('edit_roles'), 403 );

        $arr_role_permissions = $this->getArrPermissionId($role);
        $permissions = Permission::all()->toArray();

        return view('roles.edit', compact('role', 'permissions', 'arr_role_permissions'));
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
        abort_if ( Auth::user()->cannot('edit_roles'), 403 );

        $arrToValidate['name'] = 'required|string|max:255'; // |unique:roles
        $arrToValidate['display_name'] = 'required|string|max:255'; // |unique:roles
        $arrToValidate['description'] = 'required|string|max:255';

        $permissions = Permission::all()->toArray();
        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'string|max:3';
        }

        $validator = request()->validate($arrToValidate);

        $role->update([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
        ]);


        if ( $role and Auth::user()->can('edit_permissions') ) {
            $arr_role_permissions = $this->getArrPermissionId($role);
            foreach ( $permissions as $permission ) {
                
                // attach Permission
                if ( request($permission['name']) == 'on' and !in_array($permission['id'], $arr_role_permissions) ) {
                    $role->attachPermission($permission['id']);
                    
                // take Permission
                } elseif ( empty(request($permission['name'])) and in_array($permission['id'], $arr_role_permissions) ) {
                    $take_role = DB::table('permission_role')->where([
                        ['permission_id', '=', $permission['id']],
                        ['role_id', '=', $role->id],
                    ])->delete();
                }
            }
        }

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
        abort_if ( Auth::user()->cannot('delete_roles'), 403 );
        if ( $role->id < 5  ) {
            return back()->withErrors(['"' . $role->name . '" is basic role and can not be removed.']);
        }
        $role->forceDelete();
        // $role->delete();
        return redirect()->route('roles.index');
    }

    /**
     * Get permissions id
     *
     * @param  Role $role
     * @return array $arr_role_permissions
     */
    private function getArrPermissionId (Role $role) {

        $arr_role_permissions = array();
        foreach ( DB::table('permission_role')->where('role_id', $role->id)->get() as $role_permission ) {
            $arr_role_permissions[] = $role_permission->permission_id;
        };

        return $arr_role_permissions;
    }
}
