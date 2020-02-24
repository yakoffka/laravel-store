@extends('layouts.theme_switch')

@section('title', config('custom.main_title_append'))

@section('description', config('custom.main_description'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{-- {{ Breadcrumbs::render('product', $product) }} --}}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
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
            @if ( !config('settings.show_empty_category') and !$category->products->count() and !$category->children->count() )
                @continue
            @endif
            {{-- /hide empty categories --}}

            @categoryGrid(compact('category'))

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

</div>
    <section class="call-to-action-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Иностранным гражданам предоставляем временную регистрацию.</h2>
                    <p>08.07.2018 года в силу вступил новый закон о миграционном учете - Федеральный закон № 163-ФЗ от 27.06.2018 г. Данный закон уточняет понятие миграционного учета и описывает, какие объекты могут быть местом регистрации иностранных граждан. Самое главное уточнение нового закона о миграционном учете состоит в том, что иностранный гражданин теперь может вставать на миграционный учет только по месту фактического проживания.</p>
                    <!--a href="#pricing" class="slide-btn smoth-scroll">узнать цены</a-->
                </div>
            </div>
        </div>
    </section>
<div class="container">


    <h2>Контакты</h2>

    <div class="row height_" id="contacts">

        <div class="col col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <p class="h4 mb-4 center blue">{{ __('we_are_on_the_map') }}</p>

            {{-- yandex MAP --}}
            <div id="map" style="width: 100%; height: 100%;"></div>
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
            {{-- /yandex MAP --}}
        </div>

        <div class="col col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <p class="h4 mb-4 center blue">{{ __('feedback_us') }}</p>

            <form class="contact_us" method="POST" action="{{ route('home.contact_us') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">{{ __('__Your_Name_required') }}*</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="{{__('Name')}}"
                        value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">{{ __('__Your_Email_required') }}*</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="{{__('email')}}"
                        value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>{{ __('Subject') }}</label>
                    <select formControlName="contactFormSubjects" class="browser-default custom-select mb-4">
                        {{-- <option value="" disabled>Choose option</option> --}}
                        <option value="1" selected>Feedback</option>
                        <option value="2">Report a bug</option>
                        <option value="3">Feature request</option>
                        <option value="4">Feature request</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">{{ __('__Your_Message_required') }}</label><br>
                    <textarea
                        id="message"
                        name="message"
                        cols=""
                        rows=""
                        class="form-control"
                        placeholder="{{ __('__Your_Message_required') }}"
                        required
                    ></textarea>
                </div>

                <div class="form-group left_stylized_checkbox">
                    <input type="checkbox" name="copy" value="" id="copy" checked>
                    <label class="filters main_category"  for="copy">{{ __('Send me a copy of this message') }}</label>
                </div>

                <div class="form-group left_stylized_checkbox">
                    <input type="checkbox" name="accept" value="" id="accept"  onchange="document.getElementById('submit').disabled = !this.checked;">
                    <label class="filters main_category"  for="accept">{{ __('I accept') }}</label>
                </div>

                <button disabled="disabled" name="submit" id="submit" type="submit" class="btn btn-outline-primary form-control">{{ __('send_message') }}</button>

            </form>

        </div>

    </div>


    <hr class="ellipsis">


    <div class="row" id="contacts">

        <div class="col-12">
            <p class="h4 mb-4 center blue">{{ __('Contact_us') }}</p>
            <div class="row mt-4 center">
                <div class="col-4">
                    <p class="h4 mb-4"> <span class="b_border nowrap"><i class="fas fa-phone"></i> {{ __('phones') }}</span> </p>
                    <p class="list">+7 (863) 269-41-20</p>
                    <p class="list">+7 (863) 269-14-67</p>
                    <p class="list">+7 (863) 256-10-42</p>
                </div>

                <div class="col-4">
                    <p class="h4 mb-4"> <span class="b_border nowrap"><i class="fas fa-envelope"></i> {{ __('address') }}</span> </p>
                    <p class="list">542308 г. Ростов-на-Дону,</p>
                    <p class="list">ул. Машиностроительная,</p>
                    <p class="list">д. 96 строение 2</p>
                </div>

                <div class="col-4">
                    <p class="h4 mb-4"> <span class="b_border nowrap"><i class="fas fa-at"></i> {{ __('emails') }}</span> </p>
                    <p class="list">info@example.com</p>
                    <p class="list">post@example.com</p>
                    <p class="list">admin@example.com</p>
                </div>
            </div>
        </div>



    </div>

@endsection
