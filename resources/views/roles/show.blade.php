@extends('layouts.app')

@section('title', 'show role')

@section('content')
<div class="container">

    <h1>Show Role '{{ $role->name }}'</h1>


    <h2 class="blue">Parameters of the role '{{ $role->name }}':</h2>

    <div class="">
        <span class="grey">name:</span> {{ $role->name }}
    </div>

    <div class="">
        <span class="grey">display_name:</span> {{ $role->display_name }}
    </div>

    <div class="">
        <span class="grey">description:</span> {{ $role->description }}
    </div>
    <br>


    <h2 class="blue">Permissions for role '{{ $role->name }}':</h2>
    <table class="blue_table">
        <tr>
            <?php
                foreach($permissions as $i => $permission) {

                    if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
                        echo '</tr><tr><td>group: <strong>' . $permission['group'] . '</strong>
                        </td>';
                    }
                    echo '<td style="text-align: right;">' . $permission['name'] . ': </td>';
                    if ( in_array($permission['id'], $arr_role_permissions) ) {
                        echo '<td>1</td>';
                    } else {
                        echo '<td>0</td>';
                    }
                }
            ?>
        </tr>
    </table><br>

    @permission('edit_roles')
    <div class="row">
        
            @if ( $role->id < 5 )
                <button class="btn btn-outline-secondary col-sm-12">
                    <i class="fas fa-pen-nib"></i> edit role
                </button>
            @else
                <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-outline-success col-sm-12">
                    <i class="fas fa-pen-nib"></i> edit role
                </a>
            @endif
        
    </div>
    @endpermission


    <h2 class="blue">Users with role '{{ $role->name }}':</h2>
    @if($role->users->count())
        @foreach($role->users as $user)
            @if($loop->last){{ $loop->iteration }} '<strong>{{ $user->name }}</strong>'.
            @else{{ $loop->iteration }} '<strong>{{ $user->name }}</strong>', 
            @endif
        @endforeach
    @endif

</div>
@endsection