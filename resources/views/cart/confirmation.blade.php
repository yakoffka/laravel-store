@extends('layouts.theme_switch')

@section('title', 'confirmation of an order')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('cart') }}
        </div>
        <div
            class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    @if( !empty($cart) && $cart->total_qty )


        <div class="row justify-content-center"><h1>{{__('confirmation of an order')}}</h1></div>

        <div class="row">

            @if($cart->total_qty)

                <h2>In your cart {{ $cart->total_qty }} products</h2>

                <table class="blue_table">
                    <tr>
                        <th>#</th>
                        <th>img</th>
                        <th>name</th>
                        <th>qty</th>
                        <th class="actions1">action</th>
                        <th>price</th>
                        <th>amount</th>
                    </tr>

                    @foreach($cart->items as $i => $item)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('products.show', ['product' => $item['item']->id]) }}">
                                    <div class="card-img-top b_image"
                                         style="background-image: url({{ asset('storage') }}{{$item['item']->full_image_path_s}});">
                                        <div class="dummy perc100"></div>
                                        <div class="element"></div>
                                    </div>
                                </a>
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

                </table>

            @endif
        </div>

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            @textarea(['name' => 'comment', 'title' => 'комментарий к заказу', 'value' => old('description') ])
            <div class="row justify-content-center">
                <div class="col-sm-12 grey">
                    Оформляя заказ Вы подтверждаете свое согласие с <a href="#">Условиями пользования</a> и <a
                        href="#">Политикой конфиденциальности</a><br>
                    Обращаем Ваше внимание, что окончательная стоимость заказа, а также количество услуг, товаров и
                    подарков будут подтверждены после обработки заказа.
                </div>
            </div>
            <button type="submit" class="btn btn-primary form-control">{{__('confirm order')}}</button>
        </form>

    @else

    @endif

@endsection
