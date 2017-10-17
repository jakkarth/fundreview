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
    $reviews = \App\Review::allOrdered();
    return view('welcome', ['reviews'=>$reviews]);
});

Route::get('/submit', function() {
    return view('submit');
});

use Illuminate\Http\Request;

Route::post('/submit', function(Request $request) {
    //validate the basics of the form
    $validator = Validator::make($request->all(), [
        'fundraiser'=>'required|max:255',
        'name'=>'required|max:255',
        'email'=>'required|email|max:255',
        'review'=>'required|max:65535',
        'rating'=>'required']);

    if ($validator->fails()) {
        return redirect('/submit')->withErrors($validator)->withInput();
    }

    //validate that this user hasn't already submitted a review for this fundraiser
    $reviews = App\Review::where([['email','=',strtolower($request->email)], ['fundraiser','=',$request->fundraiser]])->get();
    if ($reviews->count() > 0) {
        $validator->errors()->add('fundraiser', 'You have already submitted a review for this fundraiser!');
        return redirect('/submit')->withErrors($validator)->withInput();
    }

    //validation appears to pass, save the record to the database
    $review = new \App\Review;
    $review->fundraiser = $request->fundraiser;
    $review->name = $request->name;
    $review->email = $request->email;
    $review->review = $request->review;
    $review->rating = $request->rating;
    $review->save();

    //and return us to the listing
    return redirect('/');
});