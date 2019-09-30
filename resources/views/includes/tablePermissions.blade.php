<div class="">
<?php

    echo '<table class="blue_table"><tr>';
    foreach($permissions as $i => $permission) {

        $title = $permission['description'];

        if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
            echo '</tr><tr><th>' . __($permission['group']) . '</th>';
        }

        echo '<td style="text-align: right;">';

        if ( !empty($user) and !empty($edit) ) { // for edit
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                echo '<strong title="' . $title . '">' . $permission['display_name'] . '</strong></td><td><input type="checkbox" name="' . $permission['name'] . '" checked></td>';
            } else {
                echo '<span title="' . $title . '">' . $permission['display_name'] . '</span></td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
            }

        } elseif ( !empty($user) ) { // for index/show
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                echo '<strong title="' . $title . '">' . $permission['display_name'] . '</strong></td><td><strong>1</strong></td>';
            } else {
                echo '<span title="' . $title . '">' . $permission['display_name'] . '</span></td><td>0</td>';
            }

        } else { // for create
            echo '<span title="' . $title . '">' . $permission['display_name'] . '</span></td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
        }

    }
    echo '</tr></table>';
?>
</div>