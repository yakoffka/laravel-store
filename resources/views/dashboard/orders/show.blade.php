@extends('dashboard.layouts.app')

@section('title', 'order')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @permission('view_orders')
                {{ Breadcrumbs::render('orders.adminshow', $order) }}
            @else
                {{ Breadcrumbs::render('orders.show', $order) }}
            @endpermission
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    @if( !empty($order->cart) and !empty($order->cart->total_qty) )

        <div class="row justify-content-center">
            <h1>Detail of order #{{ $order->id }}</h1>
        </div>

        <span class="grey">created:</span> {{ $order->created_at }}
        <span class="grey">updated:</span> {{ $order->updated_at }}<br>
    
        @permission('view_users')
            <span class="grey">customer:</span> {{ $order->customer->name }}<br>
        @endpermission

        <span class="grey">status of order:</span>
        @permission('edit_orders')
            @selectStatusOrder([
                'statuses' => $statuses, 
                'order' => $order, 
            ])
        @endpermission
        {{ $order->status->description }}<br>


        @if($order->cart->total_qty)

            <h2>Детали заказа:</h2>
            <table class="blue_table">

                <tr>
                    <th>#</th>
                    <th>img</th>
                    <th>name</th>
                    <th>qty</th>
                    <th>price</th>
                    <th>amount</th>
                </tr>

            @foreach($order->cart->items as $i => $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($item['item']->image)
                            <div class="cart_image b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$item['item']->id}}/{{$item['item']->image}});">
                        @else
                            <div class="cart_image b_image" style="background-image: url({{ asset('storage') }}/images/default/no-img.jpg);">
                        @endif
    
                            <div class="dummy"></div><div class="element"></div>
                        </div>       
                    </td>
                    <td>
                        <a href="{{ route('products.show', ['product' => $item['item']->id]) }}">
                            {{ $item['item']->name }}
                        </a>
                    </td>
                    <td class="center no_fl">
                        {{ $order->cart->items[$i]['qty'] }}
                    </td>
                    <td>{{ $item['item']->price }}</td>
                    <td>{{ $order->cart->items[$i]['amount'] }}</td>
                </tr>

            @endforeach

                <tr>
                    <th colspan="5">total payment</th>
                    <td>{{ $order->total_payment }}</td>
                </tr>

            </table>


            <h2>Комментарий к заказу</h2>
            <table class="blue_table">
                <tr>
                    <th colspan="6">comment</th>
                </tr>
                <tr>
                    <td colspan="6" class="ta_l">{{ $order->comment ?? '-' }}</td>
                </tr>
            </table>

            <h2>Доставка</h2>
            <table class="blue_table">
                <tr>
                    <th colspan="6">address</th>
                </tr>
                <tr>
                    <td colspan="6">{{ $order->cart->address ?? '-' }}</td>
                </tr>
            </table>
        @endif

        {{-- Events --}}
        @if( $customevents->count() )
            <h2 id="customevents">History of order #{{ $order->id }}</h2>
            @include('layouts.partials.customevents')
        @endif
        {{-- /Events --}}

    @else

        <div class="row justify-content-center">
            <h1>Youre cart is empty</h1>
        </div>
       
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <a href="{{ route('products.index') }}" class="btn btn-success">shopping</a>
            </div>
       </div>

    @endif

    {{-- <h2>chat</h2>

    @if ( Auth::user()->can('delete_orders') )
    delete this order 
    @modalConfirmDestroy([
        'btn_class' => 'btn btn-outline-danger form-group',
        'cssId' => 'delele_',
        'item' => $order,
        'action' => route('orders.destroy', ['order' => $order->id]),
    ])
    @endif --}}

@endsection
