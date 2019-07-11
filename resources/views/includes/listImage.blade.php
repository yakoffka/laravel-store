<div class="row">
    @foreach($product->images as $key => $img)
        <div 
            class="card-img-top b_image col-sm-2" 
            style="background-image: url({{
                asset('storage') . $img->path . '/' . $img->name . '-l' . $img->ext
            }});"
        >
            <div class="dummy"><h5>{{ $img->alt }}</h5></div><div class="element"></div>
        </div>
    @endforeach
</div>
