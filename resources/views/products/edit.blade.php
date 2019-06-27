@extends('layouts.app')

@section('title', 'Edit new product')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <h1>edit {{ $product->name }}</h1>
    </div>

    <div class="row">

        <div class="col-sm-12 product_card_bm">
            <div class="card">
                <form method="POST" action="{{ route('products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
                    @csrf

                    @method('PATCH')

                    @if($product->image)
                        <div class="card-img-top b_image col-sm-4" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}}_l{{ config('imageyo.res_ext') }});">
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @else
                    @endif

                    @inpImage(['value' => old('image')])

                    @input(['name' => 'name', 'value' => old('name') ?? $product->name, 'required' => 'required'])

                    @textarea(['name' => 'description', 'value' => old('description') ?? $product->description])

                    {{-- @input(['name' => 'manufacturer', 'value' => old('manufacturer') ?? $product->manufacturer->title ?? '-']) --}}
                    <div class="form-group">
                        <label for="manufacturer_id">manufacturer</label>
                        <select name="manufacturer_id" id="manufacturer_id">
                        <?php
                            foreach ( $manufacturers as $manufacturer ) {
                                if ( $product->manufacturer_id == $manufacturer->id ) {
                                    echo '<option value="' . $manufacturer->id . '" selected>' . $manufacturer->title . '</option>';
                                } else {
                                    echo '<option value="' . $manufacturer->id . '">' . $manufacturer->title . '</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>

                    @input(['name' => 'materials', 'value' => old('materials') ?? $product->materials])

                    @input(['name' => 'year_manufacture', 'type' => 'number', 'value' => old('year_manufacture') ?? $product->year_manufacture])

                    @input(['name' => 'price', 'type' => 'number', 'value' => old('price') ?? $product->price])
    
                    <div class="form-group">
                        <label for="visible">visible product</label>
                        <select name="visible" id="visible">
                            <?php
                                if ( $product->visible ) {
                                    echo '<option value="1" selected>visible</option><option value="0">invisible</option>';
                                } else {
                                    echo '<option value="1">visible</option><option value="0" selected>invisible</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id">parent category</label>
                        <select name="category_id" id="category_id">
                        <?php
                            foreach ( $categories as $parent_category ) {
                                if ( $product->category_id == $parent_category->id ) {
                                    echo '<option value="' . $parent_category->id . '" selected>' . $parent_category->title . '</option>';
                                } else {
                                    echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary form-control">edit product!</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
