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

Route::get('/', function () {
    $reviews = \App\Review::allOrdered();
    return view('welcome', ['reviews'=>$reviews]);
});

Route::get('/details/{id}', function($id) {
    $reviews = \App\Review::where('id','=',$id)->get()->first();
    $reviews = \App\Review::where('fundraiser','=',$reviews->fundraiser)->orderBy('created_at')->paginate(10);
    return view('details', ['reviews'=>$reviews]);
});

Route::get('/autocomplete', function(Request $request) {
    if (strlen($request->q) < 2) {
        return '';
    }
    $reviews = \App\Review::where('fundraiser','like',$request->q.'%')->select('fundraiser')->groupBy('fundraiser')->orderBy('fundraiser')->get();
    if ($reviews->count() < 1) {
        return '<div class="alert alert-success">Be the first to review this fundraiser!</div>';
    }
    $ret = '<div class="list-group">';
    foreach ($reviews as $r) {
        $ret .= '<a class="list-group-item list-group-item-action" onClick="selectFundraiser(\''.addslashes($r->fundraiser).'\')">'.htmlspecialchars($r->fundraiser).'</a>';
    }
    $ret .= '</div>';
    return $ret;
});

Route::get('/submit/{fundraiser?}', function($fundraiser='') {
    return view('submit', ['fundraiser'=>$fundraiser]);
});

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