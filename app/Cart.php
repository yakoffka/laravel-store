<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

class Cart // extends Model
{
    public $items = null;
    public $total_qty = 0;
    public $total_payment = 0;

    /**
     *
     * 
     */
    public function __construct( $oldCart = null )
    {
        if ( $oldCart ) {
            $this->items = $oldCart->items;
            $this->total_qty = $oldCart->total_qty;
            $this->total_payment = $oldCart->total_payment;
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
        $this->total_qty ++;
        $this->total_payment += $item->price;
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
                $this->total_qty -= $removedItem['qty'];
                $this->total_payment -= $removedItem['amount'];
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
                $this->total_qty = $this->total_qty + $changedItem['qty'] - $old_qty;
                $this->total_payment = $this->total_payment + $changedItem['amount'] - $item->price * $old_qty;
            }
        }
    }
    
}
