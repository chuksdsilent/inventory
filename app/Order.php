<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected  $guarded =[];

    public function order_details()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function products(){
        return $this->belongsTo(Product::class);
    }
}
