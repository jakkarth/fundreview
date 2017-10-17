<?php
use Illuminate\Http\Request;


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

//primary listing page
Route::get('/', function () {
    $reviews = \App\Review::allOrdered();
    return view('welcome', ['reviews'=>$reviews]);
});

//view all reviews for a particular fundraiser
Route::get('/details/{id}', function($id) {
    $reviews = \App\Review::where('id','=',$id)->get()->first();
    $reviews = \App\Review::where('fundraiser','=',$reviews->fundraiser)->orderBy('created_at')->paginate(10);
    return view('details', ['reviews'=>$reviews]);
});

//api response to autocomplete on the submit page
Route::get('/autocomplete', function(Request $request) {
    //no need to do single-character hints
    if (strlen($request->q) < 2) {
        return '';
    }

    //mysql's 'like' function is case-insensitive
    $reviews = \App\Review::where('fundraiser','like',$request->q.'%')->select('fundraiser')->groupBy('fundraiser')->orderBy('fundraiser')->get();
    if ($reviews->count() < 1) {
        //no existing reviews for this fundraiser
        return '<div class="alert alert-success">Be the first to review this fundraiser!</div>';
    }
    //build a list of existing options to hint the user towards
    $ret = '<div class="list-group">';
    foreach ($reviews as $r) {
        $ret .= '<a class="list-group-item list-group-item-action" onClick="selectFundraiser(\''.addslashes($r->fundraiser).'\')">'.htmlspecialchars($r->fundraiser).'</a>';
    }
    $ret .= '</div>';
    return $ret;
});

//submit a new review, possibly prepopulating the name
Route::get('/submit/{fundraiser?}', function($fundraiser='') {
    return view('submit', ['fundraiser'=>$fundraiser]);
});

//validate and save a new review entry
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
