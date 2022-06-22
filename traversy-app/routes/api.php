<?php

use App\Models\Listing;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware'=>['auth:sanctum']],function (){

    Route::post('products/create',[ProductController::class,'store']);
    Route::put('products/{id}',[ProductController::class,'update']);
    Route::delete('products/{id}',[ProductController::class,'update']);
    Route::get('products/user/logout',[AuthController::class,'logout']);

   
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();







});
Route::get('products/search/{name}',[ProductController::class,'search']);
Route::get('products/{id}',[ProductController::class,'show']);
Route::post('products/user/register',[AuthController::class,'register']);
Route::post('products/user/login',[AuthController::class,'login']);

// Route::post('products',function(){

//     return Product::create([
//         'title'=>'pen',
//         'slug'=>'pen',
//         'description'=>'best pen',
//         'price'=> '99.99'
//     ]);
// });

// Route::get('/main/listing',function(){
//     echo "Test api";
// });
// Route::apiResource('/test/listing',[ListingController::class,'getAllListing']);
Route::apiResource('products', ProductController::class);
// Route::apiResource('/test/listing',[ProductController::class,'getAllListing']);