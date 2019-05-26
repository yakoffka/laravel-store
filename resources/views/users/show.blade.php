@extends('layouts.app')

@section('title')
user
@endsection

@section('content')
<div class="container">

    <h1>show user {{ $user->name }}</h1>
    

    <h5>{{ $user->name }} info:</h5>
    <table class="blue_table">
        <tr>
            <th>id</th>
            <th>img</th>
            <th>name</th>
            <th>email</th>
            <th>roles</th>
            <th>permissions</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>actions</th>
        </tr>

        <tr>
            <td>{{ $user->id }}</td>
            <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->roles->count())
                    {{ $user->roles->count() }}:
                    @foreach ($user->roles as $role)
                        {{ $role->name }};
                    @endforeach
                @endif
            </td>
            <td>
                <?php
                    $num_permissions = 0;
                    foreach ($permissions as $permission) {
                        if ( $user->can($permission->name) ) { $num_permissions++; }
                    }
                    echo $num_permissions;
                ?>
            </td>
            <td>{{ $user->created_at ?? '-' }}</td>
            <td>{{ $user->updated_at ?? '-' }}</td>
            <td>
                <div class="td user_buttons row center">

                    @permission('edit_users')
                    
                        <a href="{{ route('usersEdit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-pen-nib"></i>
                        </a>
                    
                    @endpermission


                    @if ( Auth::user()->id == $user->id )
                
                        <a href="{{ route('usersEdit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-pen-nib"></i>
                        </a>

                    @endif


                    @permission('delete_users')
                    
                        <form action="{{ route('usersDestroy', ['user' => $user->id]) }}" method='POST'>
                            @csrf

                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    
                    @endpermission

                </div>
            </td>
        </tr>

    </table><br>

    <h5>{{ $user->name }} can:</h5>
    <div class="">
        <?php
            foreach ($permissions as $permission) {
                if ( $user->can($permission->name) ) { echo $permission->display_name . '; '; }
            }
        ?>
    </div>


</div>
@endsection
