
    <h3 class="blue">Операции с выделенными товарами</h3>
    <form id="products_massupdate" action="{{ route('products.massupdate') }}" method="POST">
        @csrf

        @actionProductsMassupdate(['value' => 'delete', 'i_class' => 'fa-trash', 'b_class' => 'danger', 'b_label' => 'удалить', 'mess' => 'Удалить выбранные товары?'])
        @actionProductsMassupdate(['value' => 'replace', 'i_class' => 'fa-suitcase-rolling', 'b_class' => 'success', 'b_label' => 'переместить', 'mess' => 'Выберите новую категорию:'])
        @actionProductsMassupdate(['value' => 'inseeable', 'i_class' => 'fa-eye-slash', 'b_class' => 'secondary', 'b_label' => 'скрыть', 'mess' => 'Скрыть выбранные товары?'])
        @actionProductsMassupdate(['value' => 'seeable', 'i_class' => 'fa-eye', 'b_class' => 'primary', 'b_label' => 'отобразить', 'mess' => 'Отобразить выбранные товары?'])

    </form>
