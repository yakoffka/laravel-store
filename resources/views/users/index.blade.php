@extends('layouts.app')

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


    <h1>List of users</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">
    
            <table class="blue_table overflow_x_auto">
                <tr>
                    <th>#</th>
                    <th>id</th>
                    <th>img</th>
                    <th>name</th>
                    <th>email</th>
                    <th>roles</th>
                    <th>permissions</th>
                    <th>created</th>
                    <th>updated</th>
                    <th class="actions3">actions</th>
                </tr>

                @foreach($users as $i=>$user)

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $user->id }}</td>
                    <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="#roles_{{ $user->name }}">{{ $user->roles->count() }}</a></td>
                    <td><a href="#perms_{{ $user->name }}">
                        <?php
                            $num_permissions = 0;
                            foreach ($permissions as $permission) {
                                if ( $user->can($permission->name) ) { $num_permissions++; }
                            }
                            echo $num_permissions;
                        ?>
                    </a></td>
                    <td>{{ $user->created_at ?? '-' }}</td>
                    <td>{{ $user->updated_at ?? '-' }}</td>
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
                        {{-- @if( auth()->user()->can('delete_users') or Auth::user()->id == $user->id ) --}}
                            {{-- <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" class="del_btn">
                                @csrf

                                @method("DELETE")

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delele_',
                                'item' => $user,
                                'action' => route('users.destroy', ['user' => $user->id]),
                            ])
                        {{-- @endif --}}
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


            {{-- Actions --}}
            @if($actions->count())
                <h2>table history</h2>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>№</th>
                            {{-- <th>Тип</th> --}}
                            <th>Дата</th>
                            <th>Описание</th>
                            @if ( Auth::user()->can('view_orders') )
                                <th>Инициатор</th>
                            @endif
                            {{-- <th>Наличие</th> --}}
                        </tr>
                    </thead>
                    @foreach( $actions as $action )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- <td>
                                {{ $action->action }}
                            </td> --}}
                            <td>
                                {{ $action->created_at }}
                            </td>
                            <td>
                                {{ $action->description }}
                            </td>
                            @if ( Auth::user()->can('view_orders') )
                                <td>
                                    {{ $action->getInitiator->name }}
                                </td>
                            @endif
                    @endforeach
                </table>
            @endif
            {{-- /Actions --}}


        </div>
    </div>
@endsection
