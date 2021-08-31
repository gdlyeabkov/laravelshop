<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller {
    public function update(Request $request, $id) {
        echo $request->url;
        die;
    }
}