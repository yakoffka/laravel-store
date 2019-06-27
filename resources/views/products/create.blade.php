@extends('layouts.app')

@section('title', 'Creating new product')

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

                    {{-- <div class="form-group">
                        <input type="file" name="image" accept=".jpg, .jpeg, .png" value="{{ old('image') }}">
                    </div> --}}
                    @inpImage(['value' => old('image')])

                    {{-- <div class="form-group">
                        <label for="name">name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Product" value="{{ old('name') }}" required>
                    </div> --}}
                    {{-- @inpName(['value' => old('name')]) --}}
                    @input(['name' => 'name', 'value' => old('name'), 'required' => 'required'])

                    {{-- <div class="form-group">
                        <label for="manufacturer">manufacturer</label>
                        <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="manufacturer" value="{{ old('manufacturer') }}">
                    </div> --}}
                    {{-- @input(['name' => 'manufacturer', 'value' => old('manufacturer')]) !!! manufacturer_id --}}

                    {{-- <div class="form-group">
                        <label for="materials">materials</label>
                        <input type="text" id="materials" name="materials" class="form-control" placeholder="materials" value="{{ old('materials') }}">
                    </div> --}}
                    <div class="form-group">
                        <label for="manufacturer_id">manufacturer</label>
                        <select name="manufacturer_id" id="manufacturer_id">
                        <?php
                            foreach ( $manufacturers as $manufacturer ) {
                                echo '<option value="' . $manufacturer->id . '">' . $manufacturer->title . '</option>';
                            }
                        ?>
                        </select>
                    </div>

                    @input(['name' => 'materials', 'value' => old('materials')])

                    {{-- <div class="form-group">
                        <label for="year_manufacture">year_manufacture</label>
                        <input type="number" id="year_manufacture" name="year_manufacture" class="form-control"  placeholder="year_manufacture" value="{{ old('year_manufacture') }}">
                    </div> --}}
                    @input(['name' => 'year_manufacture', 'type' => 'number', 'value' => old('year_manufacture')])

                    {{-- <div class="form-group">
                        <label for="price">price</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="price" value="{{ old('price') }}">
                    </div> --}}
                    @input(['name' => 'price', 'type' => 'number', 'value' => old('price')])
    
                    {{-- <div class="form-group">
                        <label for="description">visible product</label>
                        <select name="visible" id="visible">
                            <option value="1" selected>visible</option>
                            <option value="0">invisible</option>
                        </select>
                    </div> --}}
                    @select(['name' => 'visible', 'options' => [['value' => '1', 'title' => 'visible'], ['value' => '0', 'title' => 'invisible']]])

                    <div class="form-group">
                        <label for="description">parent category</label>
                        <select name="category_id" id="category_id">
                        <?php
                            foreach ( $categories as $parent_category ) {
                                echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                            }
                        ?>
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <label for="description">description</label>
                        <textarea id="description" name="description" cols="30" rows="4" class="form-control" placeholder="description">{{ old('description') }}</textarea>                       
                    </div> --}}
                    @textarea(['name' => 'description', 'value' => old('description')])

                    <button type="submit" class="btn btn-primary form-control">Create new product!</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
