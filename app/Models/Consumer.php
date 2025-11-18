<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    protected $table = 'consumers';
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'note',
    ];

        public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
