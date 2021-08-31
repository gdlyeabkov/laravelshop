<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\JsonResponse;



Route::get('/', function () {
    return view('index');
});



Route::get('/home', function () {
    $allProducts = DB::select('select * from products');
    return new JsonResponse([
        "allProducts" => $allProducts,
        "message" => "success"
    ]);
});

Route::get('/admin/orders', function () {
    $allOrders = DB::select('select * from orders');
    return new JsonResponse($allOrders);
});

Route::get('/admin/products/add', function () {
    DB::table('products')->insert([
        'name' => Request::get('productname'),
        'price' => Request::get('productprice')
    ]);
    return new JsonResponse([
        "message" => "success",
        "status" => "OK",
    ]);
});

Route::get('/users/amount', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    // $currentUser->moneys += Request::get('amount');
    DB::table('users')->where('email', '=', Request::get('useremail'))->update([
        "moneys" => (int)Request::get('amount') + $currentUser->moneys
    ]);
    return new JsonResponse([
        "status" => "OK",
        "moneys" => $currentUser->moneys,
    ]);
});

Route::get('/admin/products/delete', function () {
    DB::table('products')->where('name', '=', Request::get('productname'))->delete();
    return new JsonResponse([
        "status" => "OK"
    ]);
});

Route::get('/users/check', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    $passwordCheck = Hash::check(Request::get('userpassword'), $currentUser->password);
    if(Request::get('useremail') === $currentUser->email && $passwordCheck && Request::get('userpassword') !== ''){
        return new JsonResponse([
            "user" => $currentUser,
            "status" => "OK"
        ]);
    }
    return new JsonResponse([
        "status" => "Error"
    ]);
});

Route::get('/users/usercreatesuccess', function () {
    $allUsers = DB::select('select * from users');
    $userExists = false;
    foreach($allUsers as $user) {
        if($user === Request::get("useremail")){
            $userExists = true;
        }
    }
    if($userExists){
        return new JsonResponse([
            "status" => "Error"
        ]);
    } else {
        $encodedPassword = "#";
        $encodedPassword = Hash::make(Request::get("userpassword"));
        DB::table('users')->insert([
            'email' => Request::get('useremail'),
            'password' => $encodedPassword,
            'name' => Request::get('username'),
            'age' => Request::get('userage')
        ]);
        return new JsonResponse([
            "status" => "created"
        ]);
    }
});

Route::get('/users/bucket', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    $productsInBucket = json_decode($currentUser->productsInBucket);
    // $productsInBucket = [];
    return new JsonResponse([
        "productsInBucket" => $productsInBucket,
        "message" => "success"
    ]);
});

Route::get('/users/bucket/add', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    $productsInBucket = json_decode($currentUser->productsInBucket);
    array_push($productsInBucket, [
        "id" => (string)rand(1, 500),
        "name" => Request::get('productname'),
        "price" => (int)Request::get('productprice')
    ]);
    // $currentUser->productsInBucket = json_encode($productsInBucket);
    DB::table('users')->where('email', '=', Request::get('useremail'))->update([
        "productsInBucket" => json_encode($productsInBucket)
    ]);
    return new JsonResponse([
        "status" => "OK",
        "message" => "success"
    ]);
});

Route::get('/users/bucket/delete', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    $productsInBucket = json_decode($currentUser->productsInBucket);
    
    $productsInBucket = array_filter($productsInBucket, function($product) {
        return $product->id !== Request::get('productid');
    });
    // $currentUser->productsInBucket = json_encode($productsInBucket);
    DB::table('users')->where('email', '=', Request::get('useremail'))->update([
        "productsInBucket" => json_encode($productsInBucket)
    ]);
    return new JsonResponse([
        "status" => "OK",
        "message" => "success"
    ]);
});

Route::get('/product/{productID}', function ($productID) {
    $product = DB::table('products')->where('id', '=', $productID)->first();
    return new JsonResponse([
        "product" => $product,
        "message" => "success",
    ]);
});

Route::get('/users/bucket/buy', function () {
    $currentUser = DB::table('users')->where('email', '=', Request::get('useremail'))->first();
    $commonPrice = 0;
    $productsInBucket = json_decode($currentUser->productsInBucket);
    foreach($productsInBucket as $product){
        $commonPrice += $product->price;
    }
    if($currentUser->moneys >= $commonPrice) {
        DB::table('orders')->insert([
            'ownername' => Request::get('useremail'),
            'price' => $commonPrice
        ]);
        // $currentUser->moneys -= $commonPrice;
        // $currentUser->productsInBucket = "[]";
        DB::table('users')->where('email', '=', Request::get('useremail'))->update([
            "moneys" =>  (int)$currentUser->moneys - $commonPrice,
            "productsInBucket" => "[]",
        ]);
        return new JsonResponse([
            "status" => "OK",
            "message" => "success",
        ]);
    }
    return new JsonResponse([
        "status" => "Error",
        "message" => "success",
    ]);
});

Route::get('{redirectroute}', function ($redirectroute) {
    return redirect('/')->with('redirectroute', Request::path());
});

Route::get('{redirectrouteone}/{redirectroutetwo}', function ($redirectrouteone, $redirectroutetwo) {
    return redirect('/')->with('redirectroute', Request::path());
});

Route::get('{redirectrouteone}/{redirectroutetwo}/{redirectroutethree}', function ($redirectrouteone, $redirectroutetwo, $redirectroutethree) {
    return redirect('/')->with('redirectroute', Request::path());
});
