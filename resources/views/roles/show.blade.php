@extends('layouts.app')

@section('title')
role
@endsection

@section('content')
<div class="container">

    <h1>Role '{{ $role->display_name }}'</h1>

    <table class="blue_table">

        <tr>
            <th>group permissions</th>
            <th>Create</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>View</th>
        </tr>

        @foreach($groupsPerm as $i => $groupPerm)

        <tr>
            <td>Permission for {{ $groupPerm }}</td>
            <td>{{ in_array('create_' . $groupPerm , $permNames) ? '1' : '0' }}</td>
            <td>{{ in_array('edit_' . $groupPerm , $permNames) ? '1' : '0' }}</td>
            <td>{{ in_array('delete_' . $groupPerm , $permNames) ? '1' : '0' }}</td>
            <td>{{ in_array('view_' . $groupPerm , $permNames) ? '1' : '0' }}</td>
        </tr>

        @endforeach
        <tr></tr>

    </table>
</div>
@endsection
