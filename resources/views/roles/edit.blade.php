@extends('layouts.app')

@section('title')
edit role
@endsection

@section('content')
<div class="container">

    <h1>Edit Role '{{ $role->name }}'</h1>

    <form method="POST" action="{{ route('rolesUpdate', ['role' => $role->id]) }}">
        @csrf

        @method("PATCH")

        <h5 class="blue">specify the parameters of the new role:</h5>
        <div class="form-group">
            <!-- <label for="name">name</label> -->
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?? $role->name }}" required>
        </div>

        <div class="form-group">
            <!-- <label for="display_name">display_name</label> -->
            <input type="text" id="display_name" name="display_name" class="form-control" value="{{ old('display_name') ?? $role->display_name }}" required>
        </div>

        <div class="form-group">
            <!-- <label for="description">description</label> -->
            <input type="text" id="description" name="description" class="form-control" value="{{ old('description') ?? $role->description }}" required>
        </div>


        <h5 class="blue">select permissions for new role:</h5>
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
                            echo '<td><input type="checkbox" name="' . $permission['name'] . '" checked></td>';
                        } else {
                            echo '<td><input type="checkbox" name="' . $permission['name'] . '"></td>';
                        }
                    }
                ?>
                
            </tr>

        </table><br>

        <button type="submit" class="btn btn-primary form-control">edit role!</button>

    </form>

</div>
@endsection
