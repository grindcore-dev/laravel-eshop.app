<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class exchangeJson extends Model
{
    //
    protected $table = 'exchange_json';
    protected $fillable = ['data'];

    public function product()
    {
        return $this->hasOne('App\Products', 'exchange_id', 'id');
    }
}
