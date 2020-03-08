<?php

namespace App;

class Cart
{
    public $items = null;
    public int $total_qty = 0;
    public int $total_payment = 0;
    private string $event_description = '';

    /**
     * Cart constructor.
     * @param Cart|null $oldCart
     */
    public function __construct(Cart $oldCart = null)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->total_qty = $oldCart->total_qty;
            $this->total_payment = $oldCart->total_payment;
        }
    }

    public function add($item): void
    {
        $storedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ($this->items && array_key_exists($item->id, $this->items)) {
            $storedItem = $this->items[$item->id];
        }

        $storedItem['qty']++;
        $storedItem['amount'] = $item->price * $storedItem['qty'];

        $this->items[$item->id] = $storedItem;
        $this->total_qty++;
        $this->total_payment += $item->price;
        $this->event_description = __('Success_adding__to_cart', ['name' => $item->name]);
        session()->flash('message', $this->event_description);
    }

    public function remove($item): void
    {
        $removedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ($this->items && array_key_exists($item->id, $this->items)) {
            $removedItem = $this->items[$item->id];

            $removedItem['amount'] = $item->price * $removedItem['qty'];

            unset($this->items[$item->id]);
            $this->total_qty -= $removedItem['qty'];
            $this->total_payment -= $removedItem['amount'];
        }
    }

    /**
     * @param $item
     * @param $qty
     */
    public function change($item, $qty): void
    {
        $changedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ($this->items && array_key_exists($item->id, $this->items)) {
            $old_qty = $this->items[$item->id]['qty'];
            $new_qty = $qty;

            $changedItem['qty'] = $new_qty;
            $changedItem['amount'] = $item->price * $changedItem['qty'];

            $this->items[$item->id] = $changedItem;
            $this->total_qty = $this->total_qty + $changedItem['qty'] - $old_qty;
            $this->total_payment = $this->total_payment + $changedItem['amount'] - $item->price * $old_qty;
        }
    }

    /**
     *
     */
    public function setFlashMess(): void
    {
        $this->event_description = __('Task__success', [
            'name' => $this->name, 'type_act' => __('feminine_' . $this->type)
        ]);
        session()->flash('message', $this->event_description);
    }
}
