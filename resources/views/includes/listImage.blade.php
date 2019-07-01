{{ <?php
    $images = [
        ['noimg', 'alt',],
        ['noimg', 'alt',],
        ['noimg', 'alt',],
    ]
?> }}

<div class="row">
    @foreach($images as $key => $image)
        <div 
            class="card-img-top b_image col-sm-2" 
            style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}}_l{{ config('imageyo.res_ext') }});"
        >
            <div class="dummy"></div><div class="element"></div>
        </div>
    @endforeach
</div>
