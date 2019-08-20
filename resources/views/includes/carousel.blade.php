<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    
    <ol class="carousel-indicators">
        @foreach($product->images as $key => $image)
        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"{!! $key ? '' : ' class="active"' !!}></li>
        @endforeach
    </ol>

    <div class="carousel-inner">
        @foreach($product->images as $key => $img)
            <div class="carousel-item{{ $key ? '' : ' active' }}">
                <div
                    class="card-img-top b_image" 
                    style="background-image: url({{
                        asset('storage') . $img->path . '/' . $img->name . '-l' . $img->ext
                    }});"
                    title="{{ $img->alt }}">
                    <div class="dummy"></div><div class="element"></div>
                </div>
            </div>
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
