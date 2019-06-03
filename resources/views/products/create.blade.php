@extends('layouts.app')

@section('title')
Creating new product
@endsection

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <h1>Creating new product</h1>
    </div>

    <div class="row">

        <div class="col-sm-12 product_card_bm">
            <div class="card">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <input type="file" name="image" accept=".jpg, .jpeg, .png" value="{{ old('image') }}">
                    </div>

                    <div class="form-group">
                        <label for="name">name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Product" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="manufacturer">manufacturer</label>
                        <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="manufacturer" value="{{ old('manufacturer') }}">
                    </div>

                    <div class="form-group">
                        <label for="materials">materials</label>
                        <input type="text" id="materials" name="materials" class="form-control" placeholder="materials" value="{{ old('materials') }}">
                    </div>

                    <div class="form-group">
                        <label for="year_manufacture">year_manufacture</label>
                        <input type="number" id="year_manufacture" name="year_manufacture" class="form-control"  placeholder="year_manufacture" value="{{ old('year_manufacture') }}">
                    </div>

                    <div class="form-group">
                        <label for="price">price</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="price" value="{{ old('price') }}">
                    </div>
    
                    <div class="form-group">
                        <label for="description">visible product</label>
                        <select name='show' id="show">
                            <option value="1" selected>show product</option><option value="0">do not show</option>
                        </select>
                    </div>

                    <div class="form-group">
                            <label for="description">parent category</label>
                            <select name='category_id' id="category_id">
                            <?php
                                foreach ( $categories as $parent_category ) {
                                    echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">description</label>
                        <textarea id="description" name="description" cols="30" rows="4" class="form-control" placeholder="description">{{ old('description') }}</textarea>                       
                    </div>

                    <button type="submit" class="btn btn-primary form-control">Create new product!</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
