<!-- comments -->
<div class="row">
    <div class="col-md-12">
        @if($product->comments->count())
            <h2>{{__('Product_comments')}}{{ $product->name }} ({{ $product->comments->count() }})</h2>
            <ul class='content list-group'>
                @foreach ($product->comments as $num_comment => $comment)
                    @include('layouts.partials.comment_row')
                @endforeach
            </ul>
        @else
            <h2>комментарии к товару {{ $product->name }}</h2>
            <p class="grey">комментариев ещё нет — ваш может стать первым.</p>
        @endif
    </div>
</div>
<!-- /comments -->
