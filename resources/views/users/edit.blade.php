@extends('layouts.app')

@section('title', 'edit profile')

@section('content')
<div class="container">

    <h1>edit profile {{ $user->name }}</h1>

    <img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px">    
    <h5>{{ $user->name }} info:</h5>

    <form method="POST" 
        action="{{ route('users.update', ['user' => $user->id]) }}" 
        enctype="multipart/form-data">

        @csrf

        @method('PATCH')

        <table class="blue_table">
        <tr>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>roles</th>

                @permission('edit_roles')

                    <th>add role</th>
                    <th>take the role</th>

                @endpermission

                <th>created</th>
                <th>updated</th>
            </tr>

            <tr>
                <td>{{ $user->id }}</td>
                
                <td>
                    <input type="text" id="name" name="name" class="form-control" 
                    value="{{ old('name') ?? $user->name }}" required>
                </td>

                <td>
                    <input type="email" id="email" name="email" class="form-control" 
                    value="{{ old('email') ?? $user->email }}">
                </td>

                <td>
                    <?php
                        foreach ( $user->roles as $role ) {
                            echo $role->name . '; ';
                        }
                    ?>
                </td>
                
                @permission('edit_roles')
                <td>
                    <select name="role" id="role">
                        <option value="" selected>-</option>
                        <?php
                            foreach ( $roles as $role ) {
                                if ( !$user->hasRole($role->name) ) {
                                    echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                }/* else {
                                    echo '<option value="' . $role->id . '" disabled>' . $role->name . '</option>';
                                }*/
                            }
                        ?>
                    </select>
                </td>

                <td>
                    <select name="take_role" id="take_role">
                        <option value="" selected>-</option>
                        <?php
                            $num_roles = 0;
                            foreach ( $roles as $role ) {
                                if ( $user->hasRole($role->name) ) {
                                    $num_roles++;
                                }
                            }
                            foreach ( $roles as $role ) {
                                if ( $user->hasRole($role->name) ) {
                                    if ( $num_roles < 2 ) {
                                        echo '<option value="' . $role->id . '" disabled>' . $role->name . '</option>';
                                    } else {
                                        echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                    }
                                }
                            }
                        ?>
                    </select>
                </td>
                @endpermission


                <td>{{ $user->created_at ?? '-' }}</td>
                <td>{{ $user->updated_at ?? '-' }}</td>
            </tr>

        </table><br>

        @permission('edit_users')
        @else
        <div class="form-group">
            <label for="name">password user</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        @endpermission

        @permission('view_permissions')
            <h2 id="perms">Permissions for {{ $user->name }}:</h2>
            @tablePermissions(['permissions' => $permissions, 'user' => $user])
            <br><br><br>
        @endpermission

        <button type="submit" class="btn btn-primary form-control">edit profile!</button><br>

    </form>

</div>
@endsection
