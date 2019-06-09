@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <h1>{{ $cart ? 'In youre cart ' . $cart->totalQty . ' products' : 'Youre cart is empty' }}</h1>
    </div>

    <div class="row">

        @if($cart->totalQty)

            <table class="blue_table">
                <tr>
                    <th>#</th>
                    <th>img</th>
                    <th>name</th>
                    <th>qty</th>
                    <th>price</th>
                    <th>amount</th>
                </tr>

                {{-- {{dd($cart)}} --}}

            @foreach($cart->items as $i => $item)

                <tr>
                    <td>{{ $i }}</td>
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
                        <a href="{{ route('products.show', ['product' => $i]) }}">
                            {{ $item['item']->name }}
                        </a>
                    </td>
                    <td>{{ $cart->items[$item['item']->id]['qty'] }}</td>
                    <td>{{ $item['item']->price }}</td>
                    <td>{{ $cart->items[$item['item']->id]['price'] }}</td>
                </tr>

            @endforeach

            <tr>
                <td colspan="5">total amount</td>
                <td>{{ $cart->totalPrice }}</td>
            </tr>

            </table>

        @endif
    </div>


</div>
@endsection
