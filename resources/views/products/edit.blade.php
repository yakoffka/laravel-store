@extends('layouts.app')

@section('title')
Creating new product
@endsection

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <h1>edit {{ $product->name }}</h1>
    </div>

    <div class="row">

        <div class="col-sm-12 product_card_bm">
            <div class="card">
                <form method="POST" action="{{ route('productsUpdate', ['product' => $product->id]) }}" enctype="multipart/form-data">
                    @csrf

                    @method('PATCH')

                    <input type="hidden" name="edited_by_user_id" value="{{ Auth::user()->id }}">

                    @if($product->image)

                        <div class="card-img-top b_image col-sm-4" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}});">
                            <div class="dummy"></div><div class="element"></div>
                        </div>

                        <div class="form-group"> заменить изображение
                            <input type="file" name="image" accept=".jpg, .jpeg, .png"
                                value="{{ old('image') }}">
                        </div>

                    @else

                        <div class="form-group">
                            <input type="file" name="image" accept=".jpg, .jpeg, .png"
                                value="{{ old('image') }}">
                        </div>

                    @endif

                    <div class="form-group">
                        <!-- <label for="name">name</label> -->
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Product"
                            value="{{ old('name') ?? $product->name }}" required>
                    </div>


                    <div class="form-group">
                        <!-- <label for="manufacturer">manufacturer</label> -->
                        <input type="text" id="manufacturer" name="manufacturer" class="form-control"
                            placeholder="manufacturer" value="{{ old('manufacturer') ?? $product->manufacturer }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="materials">materials</label> -->
                        <input type="text" id="materials" name="materials" class="form-control"
                            placeholder="materials" value="{{ old('materials') ?? $product->materials }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="year_manufacture">year_manufacture</label> -->
                        <input type="number" id="year_manufacture" name="year_manufacture" class="form-control"
                            placeholder="year_manufacture" value="{{ old('year_manufacture') ?? $product->year_manufacture }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="price">price</label> -->
                        <input type="number" id="price" name="price" class="form-control" placeholder="price"
                            value="{{ old('price') ?? $product->price }}">
                    </div>

                    <!-- <input type="hidden" name="added_by_user_id" value=""> -->

                    <div class="form-group">
                        <!-- <label for="description">Add a comment</label> -->
                        <textarea id="description" name="description" cols="30" rows="10" class="form-control"
                            placeholder="description">{{ old('description') ?? $product->description }}</textarea>                       
                    </div>

                    <button type="submit" class="btn btn-primary form-control">edit  product!</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
