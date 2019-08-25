@extends('layouts.app')

@section('title', 'Creating new category')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 p-0 breadcrumbs">
            {{ Breadcrumbs::render('categories.create') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 p-0 searchform">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Creating new category</h1>


    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 pr-0">

            {{-- <div class="card"> --}}

                {{-- <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <!-- <input type="file" id="image" name="image" accept="image/png, image/jpeg, jpg, pdf"> -->
                        <input type="file" name="image" accept=".jpg, .jpeg, .png" value="{{ old('image') }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="name">name</label> -->
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Category" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <!-- <label for="manufacturer">manufacturer</label> -->
                        <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="manufacturer" value="{{ old('manufacturer') }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="materials">materials</label> -->
                        <input type="text" id="materials" name="materials" class="form-control" placeholder="materials" value="{{ old('materials') }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="type">category_id</label> -->
                        <input type="text" id="category_id" name="category_id" class="form-control" placeholder="category_id" value="{{ old('category_id') }}" required>
                    </div>

                    <div class="form-group">
                        <!-- <label for="year_manufacture">year_manufacture</label> -->
                        <input type="number" id="year_manufacture" name="year_manufacture" class="form-control"  placeholder="year_manufacture" value="{{ old('year_manufacture') }}">
                    </div>

                    <div class="form-group">
                        <!-- <label for="price">price</label> -->
                        <input type="number" id="price" name="price" class="form-control" placeholder="price" value="{{ old('price') }}">
                    </div>

                    <!-- <input type="hidden" name="added_by_user_id" value=""> -->

                    <div class="form-group">
                        <!-- <label for="description">Add a comment</label> -->
                        <textarea id="description" name="description" cols="30" rows="4" class="form-control" placeholder="description">{{ old('description') }}</textarea>                       
                    </div>

                    <button type="submit" class="btn btn-primary">Create new product!</button>

                </form> --}}
                
                <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <input type="file" name="image" accept=".jpg, .jpeg, .png"
                            value="{{ old('image') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="name">name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name Category"
                            value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="title">title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Title Category"
                            value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">description</label>
                        <textarea id="description" name="description" cols="30" rows="4" class="form-control"
                            placeholder="description">{{ old('description') }}</textarea>                       
                    </div>
    
                    <div class="form-group">
                        <label for="visible">visible</label>
                        <select name="visible" id="visible">
                            <option value="1">visible</option>
                            <option value="0">invisible</option>
                        </select>
                    </div>

                    <div class="form-group">
                            <label for="description">parent category</label>
                            <select name="parent_id" id="parent_id">
                            <?php
                                foreach ( $categories as $parent_category ) {
                                    echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                                }
                            ?>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-primary">create category!</button>

                </form>

            {{-- </div> --}}
        </div>
    </div>
@endsection
