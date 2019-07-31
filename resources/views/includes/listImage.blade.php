<div class="row">
    @foreach($product->images as $key => $img)
    <div class="col-sm-2">
        <div 
            class="card-img-top b_image"
            style="background-image: url({{
                asset('storage') . $img->path . '/' . $img->name . '-l' . $img->ext
            }});">
            <div class="dummy"></div><div class="element"></div>
        </div>
        <h5 title="{{ $img->alt }}">
        @php
            if (strlen($img->alt) > 10) {
                echo substr($img->alt, 0, 10) . '...';    
            } else {
                echo $img->alt;
            }
        @endphp
        </h5>
        <div class="row">
            <div class="col-sm-5">
                @modalConfirmDestroy([
                    'btn_class' => 'btn btn-outline-danger',
                    'cssId' => 'delele_',
                    'item' => $img,
                    'type_item' => 'изображение',
                    'action' => route('images.destroy', ['product' => $img->id]), 
                ])
            </div>
            <div class="col-sm-5">
                @modalChangeImage([
                    'title' => 'You can change the sort order of images by setting it here.',
                    'img' => $img,
                ])
            </div>
        </div>
    </div>

    @endforeach
</div>
    