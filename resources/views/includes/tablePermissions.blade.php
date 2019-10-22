<div class="">
<?php

    echo '<table class="blue_table"><tr>';
    foreach($permissions as $i => $permission) {

        $title = $permission['description'];
        $name = $permission['name'];
        $dname = $permission['display_name'];

        if ( empty( $permissions[$i-1] ) or $permissions[$i-1]['group'] !== $permission['group'] ) {
            echo '</tr><tr><th>' . __($permission['group']) . '</th>';
        }

        echo '<td class="boxes left_stylized_checkbox" style="text-align: left;">';

        if ( !empty($user) and !empty($edit) ) { // for edit
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                // echo '<strong title="' . $title . '">' . $dname . '</strong></td><td><input type="checkbox" name="' . $permission['name'] . '" checked></td>';
                $elem = 'strong';
                $checked = ' checked';
            } else {
                // echo '<span title="' . $title . '">' . $dname . '</span></td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
                $elem = 'span';
                $checked = '';
            }
            echo '
                <input type="checkbox" id="' . $name . '" name="' . $name . '"' . $checked . '>
                <label title="' . $title . '" for="' . $name . '"><' . $elem . '>' . $name . '</' . $elem . '>' . '</label>
            </td>';

        } elseif ( !empty($user) ) { // for index/show
            if (
                get_class($user) == 'App\User' and $user->can($permission->name)
                or 
                get_class($user) == 'App\Role' and $user->perms->contains('name', $permission['name'])
            ) {
                // echo '<strong title="' . $title . '">' . $dname . '</strong></td><td><strong>1</strong></td>';
                $elem = 'strong';
                $sq = '<i class="far fa-check-square"></i>';
            } else {
                // echo '<span title="' . $title . '">' . $dname . '</span></td><td>0</td>';
                $elem = 'span';
                $sq = '<i class="far fa-square"></i>';
            }
            echo "<$elem title='$title'>$sq $dname</$elem></td>";
            // echo "<$elem>";

        } else { // for create
            // echo '<span title="' . $title . '">' . $dname . '</span></td><td><input type="checkbox" name="' . $permission['name'] . '"></td>';
            echo '
                <input type="checkbox" id="' . $name . '" name="' . $name . '">
                <label title="' . $title . '" for="' . $name . '">' . $name . '</label>
            </td>';
        }

    }
    echo '</tr></table>';
?>
</div>