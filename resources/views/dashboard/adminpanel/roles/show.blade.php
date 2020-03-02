@extends('layouts.theme_switch')

@section('title', 'show role')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('role', $role) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Просмотр роли '{{ $role->name }}'</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <h2 class="blue">Parameters of the role '{{ $role->name }}':</h2>
            <table class="blue_table">
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>display_name</th>
                    <th>description</th>
                    <th>permissions</th>
                    <th>users</th>
                    <th>created</th>
                    <th>updated</th>
                    <th class="actions2">actions</th>
                </tr>
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->display_name }}</td>
                    <td class="description" style="max-width: 400px;">{{ $role->description }}</td>
                    <td><a href="#perms">
                        @if ($role->perms())
                            {{ $role->perms()->pluck('display_name')->count() }}
                        @else
                        0
                        @endif
                    </a></td>
                    <td><a href="#users">{{ $role->users->count() }}</a></td>
                    <td>{{ $role->created_at ?? '-' }}</td>
                    <td>{{ $role->updated_at ?? '-' }}</td>
                    <!--td>
                        {{-- <div class="td role_buttons row"> --}}


                            @if ( auth()->user()->can( ['edit_roles', 'delete_roles'], true ) )

                                {{-- <div class="col-sm-6"> --}}
                                    @if ( $role->id < 5 )
                                        <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                                    @else
                                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                    @endif
                                {{-- </div> --}}

                                {{-- <div class="col-sm-6"> --}}
                                    <form action="{{ route('roles.destroy', ['role' => $role->id]) }}" method="POST" class="del_btn">
                                        @csrf

                                        @method("DELETE")

                                        @if ( $role->id < 5 )
                                            <button type="submit" class="btn btn-outline-secondary">
                                        @else
                                            <button type="submit" class="btn btn-outline-danger">
                                        @endif

                                        <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                {{-- </div> --}}


                            @elseif ( auth()->user()->can( ['edit_roles'], true ) )

                                {{-- <div class="col-sm-12"> --}}
                                    @if ( $role->id < 5 )
                                        <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                                    @else
                                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                    @endif
                                {{-- </div> --}}

                            {{-- @else
                            - --}}
                            @endif


                        {{-- </div> --}}
                    </td-->
                    <td>

                        @if ( auth()->user()->can('edit_roles') and $role->id > 4 )
                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                        @endif


                        @if ( auth()->user()->can('delete_roles') and $role->id > 4 )

                            {{-- <form action="{{ route('roles.destroy', ['role' => $role->id]) }}" method="POST" class="del_btn">
                                @csrf

                                @method("DELETE")

                                @if ( $role->id < 5 )
                                    <button type="submit" class="btn btn-outline-secondary">
                                @else
                                    <button type="submit" class="btn btn-outline-danger">
                                @endif

                                <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delete_',
                                'item' => $role,
                                'action' => route('roles.destroy', ['role' => $role->id]),
                            ])

                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-trash"></i></button>
                        @endif

                    </td>
                </tr>


            </table><br><br><br>


            @permission('view_users')
                <h2 class="blue" id="users">Пользователи, наделённые ролью '{{ $role->name }}':</h2>
                @if($role->users->count())
                    @foreach($role->users as $user)
                        @if($loop->last){{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>.
                        @else{{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>,
                        @endif
                    @endforeach
                @else
                    нет пользователей, наделённых данной ролью.
                @endif
                <br><br><br>
            @endpermission


            @permission('view_permissions')
                <h2 class="blue" id="perms">Разрешения для роли '{{ $role->name }}':</h2>
                @tablePermissions(['permissions' => $permissions, 'user' => $role])
                <br><br><br>
            @endpermission

        </div>
    </div>
</div>
@endsection
