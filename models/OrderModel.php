<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model {
    
    protected $table = 'orders';
    protected $fillable = [
        'ownername',
        'price',
    ];

}