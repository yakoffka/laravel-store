<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

class Cart // extends Model
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    /**
     *
     * 
     */
    public function __construct( $oldCart = null )
    {
        if ( $oldCart ) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    /**
     *
     * 
     */
    public function add($item)
    {
        $storedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ( $this->items ) {
            if ( array_key_exists($item->id, $this->items) ) {
                $storedItem = $this->items[$item->id];
            }
        }

        $storedItem['qty'] ++;
        $storedItem['amount'] = $item->price * $storedItem['qty'];

        $this->items[$item->id] = $storedItem;
        $this->totalQty ++;
        $this->totalPrice += $item->price;
    }

    /**
     *
     * 
     */
    public function remove($item)
    {
        $removedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ( $this->items ) {
            if ( array_key_exists($item->id, $this->items) ) {
                $removedItem = $this->items[$item->id];

                $removedItem['amount'] = $item->price * $removedItem['qty'];

                unset($this->items[$item->id]);
                $this->totalQty -= $removedItem['qty'];
                $this->totalPrice -= $removedItem['amount'];
            }
        }
    }

    /**
     *
     * 
     */
    public function change($item, $qty)
    {
        $changedItem = [
            'qty' => 0,
            'amount' => 0,
            'item' => $item,
        ];

        if ( $this->items ) {
            if ( array_key_exists($item->id, $this->items) ) {
                $old_qty = $this->items[$item->id]['qty'];
                $new_qty = $qty;

                $changedItem['qty'] = $new_qty;
                $changedItem['amount'] = $item->price * $changedItem['qty'];

                $this->items[$item->id] = $changedItem;
                $this->totalQty = $this->totalQty + $changedItem['qty'] - $old_qty;
                $this->totalPrice = $this->totalPrice + $changedItem['amount'] - $item->price * $old_qty;
            }
        }
    }
    
}
