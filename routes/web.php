<?php

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

Route::get('/', function () {
    $reviews = \App\Review::all();
    return view('welcome', ['reviews'=>$reviews]);
});

Route::get('/submit', function() {
    return view('submit');
});

use Illuminate\Http\Request;

Route::post('/submit', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'fundraiser'=>'required|max:255',
        'name'=>'required|max:255',
        'email'=>'required|email|max:255',
        'review'=>'required|max:65535',
        'rating'=>'required']);

    if ($validator->fails()) {
        return redirect('/submit')->withErrors($validator)->withInput();
    }

    //TODO verify that this email address hasn't submitted for this fundraiser before

    $review = new \App\Review;

    $review->fundraiser = $request->fundraiser;
    $review->name = $request->name;
    $review->email = $request->email;
    $review->review = $request->review;
    $review->rating = $request->rating;
    $review->save();

    return redirect('/');
});