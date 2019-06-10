@extends('layouts.app')

@section('title', "Edit Category $category->title")

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <h1>edit '{{ $category->title }}' category</h1>
    </div>

    <div class="row">

        <div class="col-sm-12 product_card_bm">
            <div class="card">
                
                <form method="POST" action="{{ route('categories.update', ['category' => $category->id]) }}" enctype="multipart/form-data">
                    @csrf

                    @method('PATCH')

                    @if($category->image)

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->image}});">
                                <div class="dummy"></div><div class="element"></div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                        <div class="form-group"> replace image
                            <input type="file" name="image" accept=".jpg, .jpeg, .png"
                                value="{{ old('image') }}">
                        </div>

                    @else

                        <div class="col-sm-12">
                        <div class="form-group"> add image
                            <input type="file" name="image" accept=".jpg, .jpeg, .png"
                                value="{{ old('image') }}">
                        </div>

                    @endif
                    

                    <div class="form-group">
                        <label for="name">name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Product"
                            value="{{ old('name') ?? $category->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="title">title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Name Product"
                            value="{{ old('title') ?? $category->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">description</label>
                        <textarea id="description" name="description" cols="30" rows="4" class="form-control"
                            placeholder="description">{{ old('description') ?? $category->description }}</textarea>                       
                    </div>
    
                    <div class="form-group">
                        <label for="visible">visible</label>
                        <select name="visible" id="visible">
                            <?php
                                if ( $category->visible ) {
                                    echo '<option value="1" selected>visible category</option><option value="0">invisible</option>';
                                } else {
                                    echo '<option value="1">visible</option><option value="0" selected>invisible</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                            <label for="description">parent category</label>
                            <select name="parent_id" id="parent_id">
                            <?php
                                foreach ( $categories as $parent_category ) {
                                    if ( $category->parent_id == $parent_category->id ) {
                                        echo '<option value="' . $parent_category->id . '" selected>' . $parent_category->title . '</option>';
                                    } else {
                                        echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-primary">edit category!</button>

                </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
