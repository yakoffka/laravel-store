@component('mail::message')
<h2>Здраствуйте, {{ $user->name }}!</h2>
Спасибо за оформление заказа на {{ config('app.name') }}!<br>
Ваш заказ #{{ $order->id }} принят {{ $order->created_at }}. 

@component('mail::button', ['url' => route('orders.show', ['order' => $order->id])])
show
@endcomponent

<p>
    Обращаем Ваше внимание, что окончательная стоимость заказа, а также количество услуг, товаров и подарков будут подтверждены после обработки заказа.
    Перед выездом за своим заказом убедитесь, что товар поступил на склад магазина. Дополнительную информацию Вы можете получить по телефону. Наш телефон: Два-два-три, три-два-два, Два-два-три, три-два-два.
</p>

Thanks, Ваш {{ config('app.name') }}
@endcomponent