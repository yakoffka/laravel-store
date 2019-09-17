@extends('layouts.app')

@section('title', 'домашняя страница')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{-- {{ Breadcrumbs::render('product', $product) }} --}}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1></h1>

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#777"/></svg> --}}
                <img src="https://prod.dragoon.pw/storage/images/common/s/s2.png" alt="">
                <div class="container">
                    <div class="carousel-caption text-left">
                        <h1>ООО «Канат-Комплект»</h1>
                        <p>крупнейший дистрибьютер грузоподъемного оборудования в Ростове-на-Дону, Батайске, Азове, Краснодаре.</p>
                        <p><a class="btn btn-lg btn-primary" href="#catalog" role="button">грузоподъемное оборудование собственного производства</a></p>
                    </div>
                </div>
            </div>
            <!--div class="carousel-item">
                {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#777"/></svg> --}}
                <img src="https://prod.dragoon.pw/storage/images/common/s/s1.png" alt="">
                <div class="container">
                    <div class="carousel-caption">
                        <h1>Another example headline.</h1>
                        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                    </div>
                </div>
            </div-->
            <div class="carousel-item">
                {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#777"/></svg> --}}
                <img src="https://prod.dragoon.pw/storage/images/common/s/s4.png" alt="">
                <div class="container">
                    <div class="carousel-caption text-right">
                        <h1>One more for good measure.</h1>
                        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                    {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#777"/></svg> --}}
                    <img src="https://prod.dragoon.pw/storage/images/common/s/s5.png" alt="">
                    <div class="container">
                        <div class="carousel-caption text-right">
                            <h1>One more for good measure.</h1>
                            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>
                </div>
            </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>



    {{-- attention --}}
    <div class="row m-4"></div>
    <div class="row">
        <div class="attention_block_text col-lg-8 offset-lg-2 col-xs-12">
            <div class="attention_block_icon"></div>
            В ассортименте представлены товары от ведущих производителей. Качество продукции подтверждается наличием паспорта и всех необходимых сертификатов. Качество продукции подтверждается наличием паспорта и всех необходимых сертификатов.
        </div>
    </div>
    <div class="row m-4"></div>
    {{-- attention --}}
    



    {{-- catalog --}}
    <h2 id="catalog">наша продукция</h2>
        
    <div class="row">

        @foreach($categories as $category)

        {{-- hide empty categories --}}
            @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
                @continue
            @endif
            {{-- /hide empty categories --}}

            @gridCategory(compact('category'))

        @endforeach
    </div>
    {{-- catalog --}}



    {{-- attention --}}
    <div class="row m-4"></div>
    <div class="row">
        <div class="attention_block_text col-lg-8 offset-lg-2 col-xs-12">
            <div class="attention_block_icon"></div>
            В ассортименте представлены товары от ведущих производителей. Качество продукции подтверждается наличием паспорта и всех необходимых сертификатов. Качество продукции подтверждается наличием паспорта и всех необходимых сертификатов.
        </div>
    </div>
    <div class="row m-4"></div>
    {{-- attention --}}




    {{-- yandex MAP --}}
    <h2>мы на карте</h2>

    <div class="row" id="yandexmap">

        <div id="map" style="width: 100%; height: 600px;"></div>

        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=4d24c871-78b4-4844-8088-0b919c191b85" type="text/javascript"></script>
        <script type="text/javascript">
            var myMap;

            // Дождёмся загрузки API и готовности DOM.
            ymaps.ready(init);

            function init () {
                // Создание экземпляра карты и его привязка к контейнеру с
                // заданным id ("map").
                var myMap = new ymaps.Map("map", {
                    center: [47.239137, 39.824304],
                    zoom: 14
                }, {
                    searchControlProvider: 'yandex#search'
                }),
                myPlacemark = new ymaps.Placemark([47.239137, 39.824304], {
                    // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
                    balloonContentHeader: "Канат-Комплект",
                    balloonContentBody: "Грузоподъемное оборудование собственного производства",
                    balloonContentFooter: "просп. 40-летия Победы 75",
                    hintContent: "ООО Канат-Комплект"
                });

                // https://yandex.ru/maps/39/rostov-na-donu/?from=api-maps&l=sat%2Cskl&ll=39.824304%2C47.239137&origin=jsapi_2_1_74&z=18


                myMap.geoObjects.add(myPlacemark);

                // document.getElementById('destroyButton').onclick = function () {
                //     // Для уничтожения используется метод destroy.
                //     myMap.destroy();
                // };

                // отключить зум карты
                myMap.behaviors.disable('scrollZoom'); 

            }

            // function setCenter () {
            //     myMap.setCenter([57.767265, 40.925358]);
            // }

            // function setBounds () {
            //     // Bounds - границы видимой области карты.
            //     // Задаются в географических координатах самой юго-восточной и самой северо-западной точек видимой области.
            //     myMap.setBounds([[37, 38], [39, 40]]);
            // }

            function setTypeAndPan () {
                // Меняем тип карты на "Гибрид".
                myMap.setType('yandex#hybrid');
                // Плавное перемещение центра карты в точку с новыми координатами.
                myMap.panTo([62.915, 34.461], {
                    // Задержка между перемещениями.
                    delay: 1500
                });
            }

        </script>
    </div>
    {{-- /yandex MAP --}}

@endsection
