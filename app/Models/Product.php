<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'code',
        'name',
        'stock',
        'price',
        'exp',
    ];
        public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'detail_transactions', 'product_id', 'transaction_id');
    }


}
