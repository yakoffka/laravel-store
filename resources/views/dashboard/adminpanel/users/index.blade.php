@extends('dashboard.layouts.app')

@section('title', 'users')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{ Breadcrumbs::render('users.index') }}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Список пользователей ресурса</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            {{-- pagination block --}}
            @if ( empty($appends) )
                @if($users->links())
                    <div class="row col-sm-12 pagination">{{ $users->links() }}</div>
                @endif
            @else
                @if($users->appends($appends)->links())
                    <div class="row col-sm-12 pagination">{{ $users->links() }}</div>
                @endif
            @endif
            {{-- /pagination block --}}
    
            <table class="blue_table overflow_x_auto">
                <tr>
                    {{-- <th>#</th> --}}
                    <th>id</th>
                    <th>img</th>
                    <th>name</th>
                    <th>email</th>
                    <th>roles</th>
                    <th>permissions</th>
                    <th>status</th>
                    {{-- <th>created</th> --}}
                    {{-- <th>updated</th> --}}
                    <th class="actions3">actions</th>
                </tr>

                @foreach($users as $i=>$user)

                <tr>
                    {{-- <td>{{ $i+1 }}</td> --}}
                    <td>{{ $user->id }}</td>
                    <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    {{-- <td><a href="#roles_{{ $user->name }}">{{ $user->roles->count() }}</a></td> --}}
                    <td><a href="#roles_{{ $user->name }}">
                        @foreach( $user->roles as $role )
                            {{ $role->display_name }};
                        @endforeach
                    </a></td>
                    <td><a href="#perms_{{ $user->name }}">
                        <?php
                            $num_permissions = 0;
                            foreach ($permissions as $permission) {
                                if ( $user->can($permission->name) ) { $num_permissions++; }
                            }
                            echo $num_permissions;
                        ?>
                    </a></td>
                    <td>{{ $user->status }}</td>
                    {{-- <td>{{ $user->created_at ?? '-' }}</td> --}}
                    {{-- <td>{{ $user->updated_at ?? '-' }}</td> --}}
                    <td>

                        @permission('view_users')
                            <a href="{{ route('users.show', ['user' => $user->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endpermission


                        @permission('edit_users')
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        @else
                            @if ( Auth::user()->id == $user->id )
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                                    <i class="fas fa-pen-nib"></i>
                                </a>
                            @endif
                        @endpermission


                        @permission('delete_users')
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delele_',
                                'item' => $user,
                                'action' => route('users.destroy', ['user' => $user->id]),
                            ])
                        @endpermission

                    </td>
                </tr>

                @endforeach

            </table><br><br><br>


            {{-- Permissions --}}
            {{-- добавить условие для входа в цикл? --}}
            @foreach ( $users as $user ) 
            
                @permission('view_roles')
                    <h2 id="roles_{{ $user->name }}">Roles of {{ $user->name }}:</h2>
                    @foreach ($user->roles as $role)
                        @if($loop->last){{ $loop->iteration }} <a href="{{ route('roles.show', ['role' => $role->id]) }}">{{ $role->display_name }}</a>.
                        @else{{ $loop->iteration }} <a href="{{ route('roles.show', ['role' => $role->id]) }}">{{ $role->display_name }}</a>, 
                        @endif
                    @endforeach
                @endpermission


                @permission('view_permissions')
                    <h2 id="perms_{{ $user->name }}">Permissions for {{ $user->name }}:</h2>
                    @tablePermissions(['permissions' => $permissions, 'user' => $user])
                    <br><br><br>
                @endpermission

            @endforeach
            {{-- /Permissions --}}


            {{-- pagination block --}}
            @if ( empty($appends) )
                @if($users->links())
                    <div class="row col-sm-12 pagination">{{ $users->links() }}</div>
                @endif
            @else
                @if($users->appends($appends)->links())
                    <div class="row col-sm-12 pagination">{{ $users->links() }}</div>
                @endif
            @endif
            {{-- /pagination block --}}


        </div>
    </div>
@endsection
