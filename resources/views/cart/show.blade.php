@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="container">

    @if($cart and $cart->totalQty)

        <div class="row justify-content-center">
            <h1>In youre cart {{ $cart->totalQty }} products</h1>
        </div>

        <div class="row">

            @if($cart->totalQty)

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

                    {{-- {{dd($cart)}} --}}

                @foreach($cart->items as $i => $item)

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
                            @modalChangeItem([
                                'cssId' => 'change_item_' . $item['item']->id,
                                'title' => 'change items',
                                'qty' => $cart->items[$i]['qty'],
                                'product' => $item['item'],
                            ])
                        </td>
                        <td class="center no_fl">
                            {{-- <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a> --}}
                            <form action="{{ route('cart.delete-item', ['product' => $item['item']->id]) }}" method="POST" class="del_btn">
                                @csrf

                                @method("DELETE")

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $item['item']->price }}</td>
                        <td>{{ $cart->items[$i]['amount'] }}</td>
                    </tr>

                @endforeach

                <tr>
                    <td colspan="6">total amount</td>
                    <td>{{ $cart->totalPrice }}</td>
                </tr>

                </table>

            @endif
        </div>

        <h5 class="center">terms of use</h5>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci ulpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <a href="{{ route('products.index') }}" class="btn btn-success">continue shopping</a>
            </div>
            <div class="col-sm-6">
                <form method="POST" action="{{ route('orders.index') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">make an order</button>
                </form>
    
            </div>
        </div>

    @else

        <div class="row justify-content-center">
            <h1>Youre cart is empty</h1>
        </div>

       <div class="row justify-content-center">
            <div class="col-sm-6">sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt, ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, qui dolorem eum fugiat, <br>quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</div>
            <div class="col-sm-6">Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, necessitatibus saepe eveniet, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, similique.</div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <a href="{{ route('products.index') }}" class="btn btn-success">shopping</a>
            </div>
       </div>

    @endif


</div>
@endsection
