@extends('layouts.app')

@section('title', 'roles')

@section('content')
<div class="container">

    <h1>List of roles</h1>

    <h5 class="blue">Parameters of the roles:</h5>
    <table class="blue_table">
        <tr>
            <th>#</th>
            <th>id</th>
            <th>name</th>
            <th>display_name</th>
            <th>description</th>
            <th>permissions</th>
            <th>users</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>actions</th>
        </tr>

        @foreach($roles as $i=>$role)

            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->display_name }}</td>
                <td style="max-width: 300px;">{{ $role->description }}</td>
                <td>
                    @if ($role->perms())
                        {{ $role->perms()->pluck('display_name')->count() }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $role->users->count() }}</td>
                <td>{{ $role->created_at ?? '-' }}</td>
                <td>{{ $role->updated_at ?? '-' }}</td>
                <td>
                    <div class="td role_buttons row">


                        @if ( Auth::user()->can( ['view_roles', 'edit_roles', 'delete_roles'], true ) )
                            <div class="col-sm-4">
                                <a href="{{ route('roles.show', ['role' => $role->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>

                            <div class="col-sm-4">
                                @if ( $role->id < 5 )
                                    <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                                @else
                                    <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="col-sm-4">
                                <form action="{{ route('roles.destroy', ['role' => $role->id]) }}" method='POST'>
                                    @csrf

                                    @method('DELETE')

                                    @if ( $role->id < 5 )
                                        <button type="submit" class="btn btn-outline-secondary">
                                    @else
                                        <button type="submit" class="btn btn-outline-danger">
                                    @endif

                                    <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>


                        @elseif ( Auth::user()->can( ['view_roles', 'edit_roles'], true ) )

                            <div class="col-sm-4">
                                <a href="{{ route('roles.show', ['role' => $role->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>

                            <div class="col-sm-4">
                                @if ( $role->id < 5 )
                                    <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                                @else
                                    <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                @endif
                            </div>


                        @elseif ( Auth::user()->can( 'view_roles' ) )

                            <div class="col-sm-4">
                                <a href="{{ route('roles.show', ['role' => $role->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>

                        @endif


                    </div>
                </td>
            </tr>

        @endforeach

    </table>



    @permission('create_roles')
        <br><a href="{{ route('roles.create') }}" class="btn btn-outline-primary"><h5>create new roles</h5></a>
    @endpermission


    @permission('view_permissions')
        <h1 class="blue">List of Permissions:</h1>
        @foreach($arr_all_role_permissions as $name_role => $arr_role_permissions)
            <div class="row"><h5 class="blue">Permissions of the roles '{{ $name_role }}':</h5></div>
            <table class="blue_table">
                <tr>
                    <?php
                        foreach($permissions as $i => $permission) {

                            if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
                                echo '</tr><tr><td>group: <strong>' . $permission['group'] . '</strong>
                                </td>';
                            }
                            // echo '<td style="text-align: right;">' . $permission['name'] . ' (id=' . $permission['id'] . ') : </td>';
                            if ( in_array($permission['id'], $arr_role_permissions) ) {
                                echo '<td style="text-align: right;"><strong>' . $permission['name'] . '</strong> (id=' . $permission['id'] . ') : </td><td>1</td>';
                            } else {
                                echo '<td style="text-align: right;">' . $permission['name'] . ' (id=' . $permission['id'] . ') : </td><td>0</td>';
                            }
                        }
                    ?>
                </tr>
            </table><br>
        @endforeach
    @endpermission


</div>
@endsection
