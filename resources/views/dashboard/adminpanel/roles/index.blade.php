@extends('layouts.theme_switch')

@section('title', 'роли')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('roles') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Список ролей</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <h2 class="blue">Таблица ролей:</h2>
            <table class="blue_table">
                <tr>
                    <th>{{ __('id') }}</th>
                    <th>{{ __('display_name') }}</th>
                    <th>{{ __('description') }}</th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('num_permissions') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('num_users') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('creator') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('editor') }}</div></th>
                    {{-- <th>created</th> --}}
                    {{-- <th>updated</th> --}}
                    <th class="actions3">actions</th>
                </tr>

                @foreach($roles as $i=>$role)

                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->display_name }}</td>
                        <td class="description" style="max-width: 300px;">{{ $role->description }}</td>
                        <td><a href="#perms_{{ $role->name }}">
                            @if ($role->perms())
                                {{ $role->perms()->pluck('display_name')->count() }}
                            @else
                                0
                            @endif
                        </a></td>
                        <td><a href="#users_{{ $role->name }}">{{ $role->users->count() }}</a></td>
                        <td title="{{ $role->creator->name }}">{{ $role->creator->id }}</td>
                        <td title="{{ $role->editor->name ?? '' }}">{{ $role->editor->id ?? '-' }}</td>
                        {{-- <td>{{ $role->created_at ?? '-' }}</td> --}}
                        {{-- <td>{{ $role->updated_at ?? '-' }}</td> --}}

                        {{-- actions --}}
                        <td>
                            <a href="{{ route('roles.show', ['role' => $role->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if ( auth()->user()->can('edit_roles') and $role->creator->id > 1 )
                                <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                    <i class="fas fa-pen-nib"></i>
                                </a>
                            @else
                                <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                            @endif


                            @if ( auth()->user()->can('delete_roles') and $role->creator->id > 1 )
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

                @endforeach

            </table><br>


            @permission('create_roles')
                <a href="{{ route('roles.create') }}" class="btn btn-outline-primary form-control">{{__('create_new_roles')}}</a><br><br><br>
            @endpermission


            @foreach($roles as $role)

                @permission('view_users')
                    <h2 class="blue" id="users_{{ $role->name }}">Список пользователей, имеющих роль '{{ $role->name }}':</h2>
                    @if($role->users->count())
                        @foreach($role->users as $user)
                            @if($loop->last){{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>.
                            @else{{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>,
                            @endif
                        @endforeach
                    @else
                        Нет пользователей с этой ролью.
                    @endif
                    <br><br>
                @endpermission

                @permission('view_permissions')
                    <h2 class="blue" id="perms_{{ $role->name }}">Разрешения для роли '{{ $role->name }}':</h2>
                    @tablePermissions(['permissions' => $permissions, 'user' => $role])
                    <br><br><br>
                @endpermission

            @endforeach


        </div>
    </div>
</div>
@endsection
