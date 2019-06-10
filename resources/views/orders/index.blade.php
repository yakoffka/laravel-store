@extends('layouts.app')

@section('title', 'orders')

@section('content')
<div class="container">

    <h1>List of orders</h1>

    <h2 class="blue">Details orders:</h2>

    {{ dd($orders) }}
    {{-- <table class="blue_table">
        <tr>
            <th>#</th>
            <th>id</th>
            <th>name</th>
            <th>display_name</th>
            <th>description</th>
            <th>permissions</th>
            <th>rank</th>
            <th>users</th>
            <th>created</th>
            <th>updated</th>
            <th class="actions3">actions</th>
        </tr>

        @foreach($orders as $i=>$order)

            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->display_name }}</td>
                <td style="max-width: 300px;">{{ $order->description }}</td>
                <td><a href="#perms_{{ $order->name }}">
                    @if ($order->perms())
                        {{ $order->perms()->pluck('display_name')->count() }}
                    @else
                    0
                    @endif
                </a></td>
                <td>{{ $order->rank }}</td>
                <td><a href="#users_{{ $order->name }}">{{ $order->users->count() }}</a></td>
                <td>{{ $order->created_at ?? '-' }}</td>
                <td>{{ $order->updated_at ?? '-' }}</td>
                <td>

                    @if ( Auth::user()->can('view_orders') )
                        <a href="{{ route('orders.show', ['order' => $order->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    @else
                        <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                    @endif


                    @if ( Auth::user()->can('edit_orders') and $order->id > 4 )
                        <a href="{{ route('orders.edit', ['order' => $order->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-pen-nib"></i>
                        </a>
                    @else
                        <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                    @endif


                    @if ( Auth::user()->can('delete_orders') and $order->id > 4 )
                        <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="POST" class="del_btn">
                            @csrf

                            @method("DELETE")

                            @if ( $order->id < 5 )
                                <button type="submit" class="btn btn-outline-secondary">
                            @else
                                <button type="submit" class="btn btn-outline-danger">
                            @endif

                            <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-secondary"><i class="fas fa-trash"></i></button>
                    @endif

                </td>

            </tr>

        @endforeach

    </table><br>



    @permission('create_orders')
        <a href="{{ route('orders.create') }}" class="btn btn-outline-primary">create new orders</a><br><br><br>
    @endpermission





    @foreach($orders as $order)
        
        @permission('view_users')
            <h2 class="blue" id="users_{{ $order->name }}">List of users for '{{ $order->name }}' order:</h2>
            @if($order->users->count())
                @foreach($order->users as $user)
                    @if($loop->last){{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>.
                    @else{{ $loop->iteration }} <a href="{{ route('users.show', ['user' => $user->id]) }}">{{ $user->name }}</a>, 
                    @endif
                @endforeach
            @else
                no users for this order
            @endif
            <br><br>
        @endpermission

        @permission('view_permissions')
            <h2 class="blue" id="perms_{{ $order->name }}">Permissions of the '{{ $order->name }}' order:</h2>
            @tablePermissions(['permissions' => $permissions, 'user' => $order])
            <br><br><br>
        @endpermission

    @endforeach --}}


</div>
@endsection
