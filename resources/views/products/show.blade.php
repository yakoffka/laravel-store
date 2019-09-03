@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <div class="row searchform_breadcrumbs">
        {{-- <div class="col col-sm-9 breadcrumbs"> --}}
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.show', $product) }}
        </div>
        {{-- <div class="col col-sm-3 searchform"> --}}
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            <div class="d-none d-md-block">@include('layouts.partials.searchform')</div>
        </div>
    </div>

    <h1 class="<?php if(!$product->visible){echo 'hide';}?>">{{ $product->name }}</h1>

    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <div class="row">

                    {{-- col-xs-12 col-sm-6  col-md-5
                    col-xs-12 col-sm-6  col-md-4
                    col-xs-12 col-sm-12 col-md-3 --}}

                {{-- <div class="col-md-4 wrap_b_image"> --}}
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-5 mb-2 wrap_b_image">

                    @if($product->images->count())
                        @carousel(compact('product'))
                    @else
                        <div 
                            class="card-img-top b_image" 
                            style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});"
                        >
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @endif
                </div>


                {{-- <div class="col-md-5"> --}}
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 mb-2 specification">
                    {{-- <h2>specification product</h2> --}}

                    {{-- <span class="grey">manufacturer: </span>{{ $product->manufacturer ?? '-' }}<br> --}}
                    <span class="grey">manufacturer: </span>{{ $product->manufacturer->title ?? '-' }}<br>
                    <span class="grey">materials: </span>{{ $product->materials ?? '-' }}<br>
                    <span class="grey">category: </span><a href="{{ route('categories.show', ['category' => $product->category->id]) }}">{{ $product->category->name}}</a><br>
                    <span class="grey">visible: </span>{{ $product->visible ? 'visible' : 'invisible' }}<br>
                    <span class="grey">year_manufacture: </span>{{ $product->year_manufacture ?? '-' }}<br>
                    <span class="grey">vendor code (id): </span>{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}<br>

                    {{-- @if($product->price)
                        <span class="grey">price: </span>{{ $product->price }} &#8381;<br>
                    @else
                        <span class="grey">цену уточняйте у менеджера</span><br>
                    @endif --}}
                    <span class="grey">цену уточняйте у менеджера</span><br>


                    @permission('edit_products')

                        <!-- created_at -->
                        <span class="grey">added by: </span>{{ $product->creator->name }}<br>
                        <span class="grey">date added: </span>{{ $product->created_at }}<br>

                        @if($product->updated_at != $product->created_at)

                            <!-- updated_at -->
                            <span class="grey">updated by: </span>{{ $product->editor->name ?? '-' }}<br>
                            <span class="grey">date updated: </span>{{ $product->updated_at }}<br>

                        @endif

                    @endpermission


                    <div class="row product_buttons">

                        @guest

                            <div class="col-sm-12">
                                @addToCart(['product_id' => $product->id])
                            </div>

                        @else

                            @if ( Auth::user()->can( ['edit_products', 'delete_products'], true ) )

                                <div class="col-sm-4">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                </div>

                                <div class="col-sm-4">
                                    {{-- <!-- form delete product -->
                                    <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                                        @csrf

                                        @method("DELETE")

                                        <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i> delete
                                        </button>
                                    </form> --}}
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger form-control',
                                        'cssId' => 'delele_',
                                        'item' => $product,
                                        'action' => route('products.destroy', ['product' => $product->id]), 
                                    ]) 
                                </div>

                                <div class="col-sm-4">
                                    <a href="{{ route('products.copy', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                </div>
                                
                            @elseif ( Auth::user()->can('edit_products') )

                                <div class="col-sm-12 padding_left_0">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i> edit
                                    </a>
                                </div>
                                
                            @else

                                <div class="col-sm-12 padding_left_0">
                                    @addToCart(['product_id' => $product->id])
                                </div>

                            @endif

                        @endguest

                    </div>
                </div>


                {{-- информация о доставке на экранах lg --}}
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                    <div class="d-none d-lg-block">{{-- d-none d-lg-block - Скрыто на экранах меньше lg --}}
                        @include('layouts.partials.shipping')
                    </div>
                </div>
                {{-- /информация о доставке на экранах lg --}}


            </div><br>

            {{-- <div class="row">
                <div class="col-md-12">
                    <h2>description {{ $product->name }}</h2>
                    <p>{{ $product->description }}</p>
                </div>
            </div> --}}




            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        Описание {{ $product->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                        Характеристики
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                        Условия работы
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    {{ $product->description }}
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <h2 class="ta_c">Характеристики {{ $product->name }}</h2>
                    <table class="param"><tbody><tr><th>Г/п, т</th><th>Ширина ленты, мм</th><th>Минимальная длина L, м</th><th>Длина петли l, мм</th></tr><tr><td>1,0</td><td>30</td><td>1,0</td><td>250</td></tr><tr><td>2,0</td><td>60</td><td>1,0</td><td>350</td></tr><tr><td>3,0</td><td>90</td><td>1,0</td><td>400</td></tr><tr><td>4,0</td><td>120</td><td>1,5</td><td>450</td></tr><tr><td>5,0</td><td>150</td><td>1,5</td><td>450</td></tr><tr><td>6,0</td><td>180</td><td>1,5</td><td>500</td></tr><tr><td>8,0</td><td>240</td><td>2,0</td><td>500</td></tr><tr><td>10,0</td><td>300</td><td>2,0</td><td>550</td></tr><tr><td>12,0</td><td>300</td><td>2,0</td><td>600</td></tr><tr><td>15,0</td><td>300</td><td>2,5</td><td>600</td></tr><tr><td>20,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>25,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>30,0</td><td>300/600</td><td>6,0</td><td>600</td></tr></tbody></table>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    

                    <h2 class="ta_c">Условия работы и меры безопасности</h2>
                    <p>Для производства текстильных строп чаще всего используют ленту на основе полиэстера (PES). Стропы из данного материала имеют ограничение на применение по температурному режиму - не допускается использование текстильных строп при температуре выше 100°С, минимальная температура для использования строп -80°С.</p>
                    <p>При строповке груза и его подъеме рекомендуется избегать рывков и ударов/ При работе с максимальной нагрузкой допускается удлинение стропа не более 6%, вне зависимости от грузоподъемности.</p>
                    <p>Для предохранения лент стропа от истирания они могут быть обшиты защитными оболочками, обеспечивающими дополнительную защиту лент, но не оказывающего влияния на разрывное усилие стропа. Строп может дополнительно комплектоваться защитными чехлами для транспортировки грузов с острой кромкой.</p>
                    <p>При строповке груза со сложной геометрией необходимо учитывать расположение центра тяжести.</p>
                    
                    <h2 class="ta_c">Запрещается:</h2>
                    <p>завязывать узлы и перекручивать стропы при эксплуатации</p>
                    <p>работы в щелочных средах</p>
                    <p>эксплуатация в средах с концентрацией пыли более 10мг/м<sup>3</sup></p>
                    <p>использование стропов с поперечными порезами и разрывами</p>
                    <p>ремонт эксплуатирующей организацией</p>

                    <h2 class="ta_c">Стропы не допускаются к работе, если:</h2>
                    <p>отсутствует маркировочная бирка</p>
                    <p>загрязнение ленты нефтепродуктами, смолами, красками более 50%</p>
                    <p>длина порезов или разрыва более 50мм, сумарная длина продольных порезов и разрывов более 10% от L1</p>
                    <p>более трех сквозных отверстий (прокол, прожиг) диаметром более 10% от b или при расстоянии между ними менее 10% от b</p>
                    <p>поверхностные обрывы и выпучивание нитей ленты длиной более 10% от b</p>
                    <p>повреждение лент от воздействия химических веществ общей длиной более 10% от b или повреждения более 50мм</p>
                    <p>отслоение края ленты или сшивки лент у петли на длине более 10% от L2</p>
                    <p>местные расслоения в местах заделки краев ленты на длине более 20мм с разрывом трех и более строчек одного крайнего или двух и более внутренних швов</p>
                    <p>местные расслоения лент на суммарной длине более 50мм с разрывом трех и более строчек одного крайнего или двух и более внутренних швов</p>
                    <p>размочаливание или износ более 10% от ширины петель стропа</p>


                </div>
            </div>



            {{-- информация о доставке на экранах уже lg --}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                <div class="d-lg-none .d-xl-block">{{-- d-none d-lg-block - Скрыт только на lg --}}
                    @include('layouts.partials.shipping')
                </div>
            </div>
            {{-- /информация о доставке на экранах lg --}}


            <!-- /product -->
            @include('layouts.partials.separator')



            <!-- comments -->
            <div class="row">
                <div class="col-md-12">

                    @if($product->comments->count())

                        <h2>Комментарии к товару {{ $product->name }} ({{ $product->comments->count() }})</h2>
                        <ul class='content list-group'>

                        @foreach ($product->comments as $num_comment => $comment)
                            <li class="list-group-item" id="comment_{{ $comment->id }}" >
                                <div class="comment_header">

                                    @if($comment->user_id == 0)
                                        Гость {{ $comment->user_name }}
                                    @else
                                        {{ $comment->creator ? $comment->creator->name : 'RIP' }}
                                    @endif


                                    <!-- created_at/updated_at -->
                                    @if($comment->updated_at == $comment->created_at)
                                        опубликвано {{ $comment->created_at }}:
                                    @else
                                        опубликвано {{ $comment->created_at }} (редактировано: {{ $comment->updated_at }}):
                                    @endif

                                    @auth
                                        @if( $comment->creator and $comment->creator->id == Auth::user()->id )
                                        <span class="blue">Ваш комментарий</span>
                                        @endif
                                    @endauth

                                    <div class="comment_buttons">

                                        <div class="comment_num">#{{-- $comment->id --}}{{ $num_comment+1 }}</div>

                                        <?php if ( (Auth::user() and Auth::user()->can('create_products') or Auth::user() and Auth::user()->id == $comment->user_id )) { ?>

                                            <!-- button edit -->
                                            <button type="button" class="btn btn-outline-success edit" data-toggle="collapse" 
                                                data-target="#collapse_{{ $comment->id }}" aria-expanded="false" aria-controls="coll"
                                            >
                                                <i class="fas fa-pen-nib"></i>
                                            </button>

                                        <?php } ?>

                                        @permission('delete_comments')
                                        <!-- delete comment -->
                                        <form action="{{ route('comments.destroy', ['comment' => $comment->id]) }}" method="POST">
                                            @csrf

                                            @method("DELETE")

                                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endpermission
                                    </div>

                                </div>

                                <div class="comment_str">{!! $comment->comment_string !!}</div>{{-- enable html entities!! --}}
                                        
                                <?php if ( (Auth::user() and Auth::user()->can('create_products') or Auth::user() and Auth::user()->id == $comment->user_id )) { ?>

                                    <!-- form edit -->
                                    <form action="/comments/{{ $comment->id }}" method="POST" class="collapse" id="collapse_{{ $comment->id }}">

                                        @method("PATCH")
                                        
                                        @csrf

                                        <textarea id="comment_string_{{ $comment->id }}" name="comment_string" cols="30" rows="4" 
                                            class="form-control card" placeholder="Add a comment"><?php echo str_replace('<br>', "\r\n", $comment->comment_string); ?></textarea>
                                        <button type="submit" class="btn btn-success">редактировать</button>
                                    </form>
                                <?php } ?>

                            </li>

                        @endforeach

                        </ul>

                    @else

                        <h2>Комментарии к товару {{ $product->name }}</h2>
                        <p class="grey">Для этого товара нет комментариев</p>

                    @endif

                </div>
            </div>
            <!-- /comments -->


            <!-- comment on -->
            <div class="row">
                <div class="col-md-12">

                    <h2>оставьте свой комментарий</h2>

                    <form method="POST" action="/products/{{ $product->id }}/comments">
                        @csrf

                        @auth
                        @else

                            <div class="form-group">
                                <!-- <label for="user_name">Your name</label> -->
                                <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Your name" value="{{ old('user_name') }}" required>
                            </div>

                        @endauth

                        <div class="form-group">
                            <!-- <label for="comment_string">Add a comment</label> -->
                            <textarea id="comment_string" name="comment_string" cols="30" rows="4" class="form-control" placeholder="Add a your comment" required>{{ old('comment_string') }}</textarea>                       
                        </div>
                        <button type="submit" class="btn btn-primary">отправить</button>
                    </form>

                </div>
            </div>
            <!-- /comment on -->
        </div>
        
    </div>{{-- <div class="row"> --}}

@endsection
