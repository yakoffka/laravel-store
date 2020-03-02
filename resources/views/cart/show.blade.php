@extends('layouts.theme_switch')

@section('title', 'Cart')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('cart') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    @if( !empty($cart) and $cart->total_qty )

        <div class="row justify-content-center">
            <h1>In youre cart {{ $cart->total_qty }} products</h1>
        </div>

        <div class="row">

            @if($cart->total_qty)

                <table class="blue_table">
                    <tr>
                        <th>#</th>
                        <th>img</th>
                        <th>name</th>
                        <th>qty</th>
                        <th class="actions2">action</th>
                        <th>price</th>
                        <th>amount</th>
                    </tr>

                @foreach($cart->items as $i => $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($item['item']->image)
                            <div class="cart_image b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$item['item']->id}}/{{$item['item']->image}}_s{{ config('imageyo.res_ext') }});">
                            @else
                            <div class="cart_image b_image" style="background-image: url({{ asset('storage') }}/images/default/noimg-l.png);">
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
                            @modalChangeItem([
                                'cssId' => 'change_item_' . $item['item']->id,
                                'title' => 'change items',
                                'qty' => $cart->items[$i]['qty'],
                                'product' => $item['item'],
                                'min' => 1,
                            ])
                        </td>
                        <td class="center no_fl">

                            {{-- <form action="{{ route('cart.delete-item', ['product' => $item['item']->id]) }}" method="POST" class="del_btn">

                                @csrf

                                @method("DELETE")

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}

                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger form-control',
                                'cssId' => 'delete_',
                                'item' => $item['item'],
                                'type_item' => 'товар',
                                'action' => route('cart.delete-item', ['product' => $item['item']->id]),
                            ])

                        </td>
                        <td>{{ $item['item']->price }}</td>
                        <td>{{ $cart->items[$i]['amount'] }}</td>
                    </tr>

                @endforeach

                <tr>
                    <td colspan="6">total amount</td>
                    <td>{{ $cart->total_payment }}</td>
                </tr>

                {{-- <tr>
                    <td colspan="6">total_qty</td>
                    <td>{{ $cart->total_qty }}</td>
                </tr> --}}

                </table>

            @endif
        </div>

        @guest
            @alert(['type' => 'primary', 'title' => 'Achtung!'])
                Для оформления заказа необходима авторизация
            @endalert
        @endguest

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <a href="{{ route('products.index') }}" class="btn btn-success form-control">continue shopping</a>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('cart.confirmation') }}" class="btn btn-primary form-control">make an order</a>
            </div>
        </div>

    @else

        <div class="row justify-content-center">
            <h1>Youre cart is empty</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">
                <a href="{{ route('products.index') }}" class="btn btn-success form-control">shopping</a>
            </div>
       </div>

        <div class="wrap_panda h300">
            <div class="panda">
                <img src="https://yakoffka.ru/src/img/links/panda-waving.png" alt="" srcset="">
            </div>
        </div>

    @endif


@endsection
