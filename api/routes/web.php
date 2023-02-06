<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'v3/dariview/');
Route::get('v3/dariview/',function () {
    return view('dariviewhome');
});
Route::get('v3/dariview/profile/{id}/t/{tk}', 'Users@employe');
Route::get('categories/{u}/{p}/', 'Users@categories');
Route::get('lp/{u}/{p}/{id}/', 'Users@listproducts');
Route::get('create/{email}/', 'Users@create');
Route::get('infos/{nickename}/', 'Users@infos');