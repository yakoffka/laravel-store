@extends('layouts.theme_switch')

@section('title', __('AllOrders'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @permission('view_orders')
                {{ Breadcrumbs::render('orders.adminindex') }}
            @else
                {{ Breadcrumbs::render('orders.index') }}
            @endpermission
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    @if( $orders->count() )

        <h1>List of orders</h1>


        {{-- pagination block --}}
        @if($orders->links())
            <div class="row col-sm-12 pagination">{{ $orders->links() }}</div>
        @endif

        <table class="blue_table">
            <tr>
                <th>name</th>
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
                    <td>{{ __('Order_name_#', ['name' => $order->name]) }}</td>
                    @permission('edit_orders')
                        @selectStatusOrder([
                            'statuses' => $statuses, 
                            'order' => $order, 
                        ])
                    @else
                        <td>{{ __($order->status->name) }}</td>
                    @endpermission
                    @permission('view_orders')
                        <td>
                            {{-- показать ордера пользователя --}}
                            <a href="{{ route('users.show', ['user' => $order->customer->id]) }}">{{ $order->customer->name }}</a>
                        </td>
                    @endpermission
                    <td>{{ $order->total_qty ?? '-' }}</td>
                    <td>{{ $order->total_payment ?? '-' }}</td>
                    <td><span class="nowrap">{{ $order->created_at ?? '-' }}</span></td>
                    <td><span class="nowrap">{{ $order->updated_at ?? '-' }}</span></td>
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

                        {{-- @if ( Auth::user()->can('view_orders') or auth()->user()->id == $order->customer_id )
                            <a href="{{ route('orders.adminshow', ['order' => $order->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                        @endif --}}
                        @if ( Auth::user()->can('view_orders') )
                            <a href="{{ route('orders.adminshow', ['order' => $order->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <a href="{{ route('orders.show', ['order' => $order->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        {{-- @if ( Auth::user()->can('edit_orders') )
                            <a href="{{ route('orders.edit', ['order' => $order->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary"><i class="fas fa-pen-nib"></i></button>
                        @endif --}}

                        @if ( Auth::user()->can('delete_orders') )
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delele_',
                                'item' => $order,
                                'action' => route('orders.destroy', ['order' => $order->id]),
                                'name_item' => 'order #' . $order->id, 
                            ])
    
                        @else
                        @endif

                    </td>
                </tr>

            @endforeach

        </table><br>
    @else

        <h1>Your list of orders is empty</h1>
    
    @endif

@endsection
