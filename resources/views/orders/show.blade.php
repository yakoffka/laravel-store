@extends('layouts.app')

@section('title', 'order')

@section('content')
<div class="container">

    <h1>Detail of order #{{ $order->id }}</h1>

    {{-- <h2 class="blue">Details order:Detail of order</h2> --}}

    {{ dd($order) }}


</div>
@endsection
