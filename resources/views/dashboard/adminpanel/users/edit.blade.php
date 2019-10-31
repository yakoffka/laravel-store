@extends('dashboard.layouts.app')

@section('title', __('editing_profile', ['name' => $user->name ]))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{ Breadcrumbs::render('users.edit', $user) }}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>

<div class="container">

    <h1>{{ __('editing_profile', ['name' => $user->name ]) }}</h1>

    
    <h2>{{ $user->name }} info:</h2>

    <form method="POST" 
        action="{{ route('users.update', ['user' => $user->id]) }}" 
        enctype="multipart/form-data">

        @csrf

        @method('PATCH')

        <table class="blue_table">
            <tr>
                <th>id</th>
                <th>img</th>
                <th>name</th>
                <th>email</th>
                <th>roles</th>

                {{-- @permission('edit_roles') --}}
                @if ( auth()->user()->can('edit_roles') and auth()->user()->id !== $user->id )
                    <th>add role</th>
                    <th>take the role</th>
                @endif
                {{-- @endpermission --}}

                <th>created</th>
                <th>updated</th>
            </tr>

            <tr>
                <td>{{ $user->id }}</td>
                <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
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
                
                @if ( auth()->user()->can('edit_roles') and auth()->user()->id !== $user->id )
                {{-- @permission('edit_roles') --}}
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
                @endif
                {{-- @endpermission --}}


                <td>{{ $user->created_at ?? '-' }}</td>
                <td>{{ $user->updated_at ?? '-' }}</td>
            </tr>

        </table>

        {{-- @permission('edit_users')   
        @else
            <div class="form-group">
                <label for="name">password user</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
        @endpermission --}}
        <div class="form-group">
            <label for="name">password user</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary form-control">{{ __('edit_profile') }}</button><br><br><br>

        @permission('view_permissions')
            <h2 id="perms">Permissions for {{ $user->name }}:</h2>
            @tablePermissions(['permissions' => $permissions, 'user' => $user])
            <br><br><br>
        @endpermission

    </form>

</div>
@endsection
