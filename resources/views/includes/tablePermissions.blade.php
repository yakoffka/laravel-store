<div class="">
<?php

    echo '<table class="blue_table"><tr>';
    foreach($permissions as $i => $permission) {

        // if ( Auth::user() and Auth::user()->can('edit_roles')) {
        //     $ip = ' (id=' . $permission['id'] . ')';
        // } else {
        //     $ip = '';
        // }
        $ip = '';

        if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
            echo '</tr><tr><th>' . $permission['group'] . '</th>';
        }

        echo '<td style="text-align: right;">';

        if ( !empty($user) and !empty($edit) ) { // for edit
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                echo '<strong>' . $permission['display_name'] . '</strong>' . $ip . ': </td><td><input type="checkbox" name="' . $permission['name'] . '" checked></td>';
            } else {
                echo $permission['display_name'] . $ip . ': </td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
            }

        } elseif ( !empty($user) ) { // for index/show
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                echo '<strong>' . $permission['display_name'] . '</strong>' . $ip . ': </td><td><strong>1</strong></td>';
            } else {
                echo $permission['display_name'] . $ip . ': </td><td>0</td>';
            }

        } else { // for create
            echo $permission['display_name'] . $ip . ': </td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
        }

    }
    echo '</tr></table>';
?>
</div>