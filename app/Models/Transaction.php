<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['transaction_number','user_id','consumer_id','total_price','date'];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'detail_transactions', 'transaction_id', 'product_id');
    }
    public function transactionDetails()
    {
        return $this->hasMany(DetailTransaction::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function consumers()
    {
        return $this->belongsToMany(Product::class, 'transaction_id', 'con_id');
    }
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }
}
