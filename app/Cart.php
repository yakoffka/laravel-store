<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

class Cart // extends Model
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if ( $oldCart ) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item)
    {
        $storedItem = [
            'qty' => 0,
            'price' => $item->price,
            'item' => $item,
        ];

        if ( $this->items ) {
            if ( array_key_exists('id', $this->items) ) {
                $storedItem = $this->items[$item->id];
            }
        }

        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$item->id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->price;
    }
}
