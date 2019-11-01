@extends('layouts.app')

@section('title', 'confirmation of an order')

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
            <h1>confirmation of an order</h1>
        </div>

        <div class="row">

            @if($cart->total_qty)

            <h2>In youre cart {{ $cart->total_qty }} products</h2>

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
                            <div class="cart_image b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$item['item']->id}}/{{$item['item']->image}});">
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
                                'cssId' => 'delele_',
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

                </table>

            @endif
        </div>

        <form method="POST" action="{{ route('orders.store') }}">

            @csrf

            @textarea(['name' => 'comment', 'title' => 'комментарий к заказу', 'value' => old('description') ])

            <h5 class="center">terms of use</h5>
            <div class="row justify-content-center">
                <div class="col-sm-12 grey center">
                    Оформляя заказ Вы подтверждаете свое согласие с Условиями пользования и Политикой конфиденциальности<br>
                    Обращаем Ваше внимание, что окончательная стоимость заказа, а также количество услуг, товаров и подарков будут подтверждены после обработки заказа.
                </div>
            </div>

            <button type="submit" class="btn btn-primary form-control">confirm</button>

        </form>

    @else

    @endif

@endsection
