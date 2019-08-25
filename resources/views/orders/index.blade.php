@extends('layouts.app')

@section('title', 'orders')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 p-0 breadcrumbs">
            {{ Breadcrumbs::render('orders.index') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 p-0 searchform">
            @include('layouts.partials.searchform')
        </div>
    </div>

{{-- <div class="container"> --}}

    {{-- {{dd($orders->count())}} --}}
    @if( $orders->count() )

        <h1>List of orders</h1>

        <h2 class="blue">Details orders:</h2>


        <!-- pagination block -->
        @if($orders->links())
            <div class="row col-sm-12 pagination">{{ $orders->links() }}</div>
        @endif


        <table class="blue_table">
            <tr>
                <th>#</th>
                <th>id</th>
                <th>status</th>
                @permission('view_orders')
                    <th>user</th>
                @endpermission
                <th>total_qty</th>
                <th>total_payment</th>
                <th>created</th>
                <th>updated</th>
                <th>comment</th>
                @if ( Auth::user()->can('delete_orders') )
                    <th class="actions2">action</th>
                @else
                    <th class="actions1">action</th>
                @endif
            </tr>

            @foreach($orders as $i=>$order)

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $order->id }}</td>
                    @permission('edit_orders')
                        @selectStatusOrder([
                            'statuses' => $statuses, 
                            'order' => $order, 
                        ])
                    @else
                        <td>{{ $order->status->name ?? '-' }}</td>
                    @endpermission
                    @permission('view_orders')
                        <td>
                            {{-- показать ордера пользователя --}}
                            <a href="{{ route('users.show', ['user' => $order->customer->id]) }}">{{ $order->customer->name }}</a>
                        </td>
                    @endpermission
                    <td>{{ $order->total_qty ?? '-' }}</td>
                    <td>{{ $order->total_payment ?? '-' }}</td>
                    <td>{{ $order->created_at ?? '-' }}</td>
                    <td>{{ $order->updated_at ?? '-' }}</td>
                    <td>
                        @if( $order->comment )
                            @modalMessage([
                                'cssId' => 'comm_' . $order->id,
                                'title' => 'комментарий к заказу №' . $order->id,
                                // 'title' => mb_substr($order->comment, 0, 20) . '...',
                                'message' => $order->comment,
                                ])
                        @else
                        -
                        @endif
                    </td>
                    <td>

                        @if ( Auth::user()->can('view_orders') or auth()->user()->id == $order->user_id )
                            <a href="{{ route('orders.show', ['order' => $order->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                        @endif


                        {{-- @if ( Auth::user()->can('edit_orders') )
                            <a href="{{ route('orders.edit', ['order' => $order->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                        @endif --}}


                        @if ( Auth::user()->can('delete_orders') )
                            {{-- <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="POST" class="del_btn">
                                @csrf

                                @method("DELETE")

                                @if ( $order->id < 5 )
                                    <button type="submit" class="btn btn-outline-secondary">
                                @else
                                    <button type="submit" class="btn btn-outline-danger">
                                @endif

                                <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delele_',
                                'item' => $order,
                                'action' => route('orders.destroy', ['order' => $order->id]),
                                'name_item' => 'order #' . $order->id, 
                            ])
    
                        @else
                            {{-- <button class="btn btn-outline-secondary"><i class="fas fa-trash"></i></button> --}}
                        @endif

                    </td>
                </tr>

            @endforeach

        </table><br>
    @else

        <h1>Your list of orders is empty</h1>

        <div class="wrap_panda">
            <div class="panda">
                <img src="https://yakoffka.ru/src/img/links/panda-waving.png" alt="" srcset="">
            </div>
        </div>
    
    @endif

{{-- </div> --}}
@endsection
