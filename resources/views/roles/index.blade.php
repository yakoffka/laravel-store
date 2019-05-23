@extends('layouts.app')

@section('title')
roles
@endsection

@section('content')
<div class="container">

    <h1>List of roles</h1>
    
    <table class="blue_table">
        <tr>
            <th>#</th>
            <th>id</th>
            <th>name</th>
            <th>display_name</th>
            <th>description</th>
            <th>permissions</th>
            <!-- <th>created_at</th>
            <th>updated_at</th> -->
            <th>actions</th>
        </tr>

        @foreach($roles as $i=>$role)

            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->display_name }}</td>
                <td>{{ $role->description }}</td>
                <td>
                    @if ($role->perms())
                    
                        <?php
                            // var_dump($role->perms()->first()->display_name);
                        ?>

                        <!-- @foreach($role->perms()->pluck('display_name') as $j => $permission)
                            
                            @if( $j < 3 )
                            {{ $permission }},
                            @else
                            
                            @endif
                            
                        @endforeach

                        еще {{ $role->perms()->pluck('display_name')->count() - 3 }} разрешений -->

                        {{ $role->perms()->pluck('display_name')->count() }} разрешений
                    
                    @else
                    -
                    @endif
                </td>
                <!-- <td>{{ $role->created_at ?? '-' }}</td>
                <td>{{ $role->updated_at ?? '-' }}</td> -->
                <td>
                    <div class="td role_buttons row">

                        <div class="col-sm-4">
                            <a href="{{ route('rolesShow', ['role' => $role->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('rolesEdit', ['role' => $role->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <form action="{{ route('rolesDestroy', ['role' => $role->id]) }}" method='POST'>
                                @csrf

                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </td>
            </tr>

        @endforeach

    </table>
</div>
@endsection
