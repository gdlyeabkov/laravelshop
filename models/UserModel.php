<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model {
    
    // public string $id;
    // public string $email;
    // public string $password;
    // public string $name;
    // public int $age;
    // public int $moneys;
    // public string $productsInBucket;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'name',
        'age',
        'moneys',
        'productsInBucket',
    ];

}