<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = ['exchange_id'];

    public function exchangeJson()
    {
        return $this->belongsTo('App\ExchangeJson', 'id', 'exchange_id');
    }

}
