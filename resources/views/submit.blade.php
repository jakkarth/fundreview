@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <h1>Submit a review!</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="/submit" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="fundraiser">Name of the Fundraiser</label>
                    <input type="text" class="form-control" id="fundraiser" name="fundraiser" placeholder="Booster" value="{{ old('fundraiser') }}">
                </div>
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="email">Your Email Address</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="JohnDoe@example.org" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="review">Your Review of the Fundraiser</label>
                    <textarea class="form-control" id="review" name="review" placeholder="Write a few paragraphs here...">{{ old('review') }}</textarea>
                </div>
   <div class="form-group">
    <label for="starz">Your Rating</label><br>
    <div id="starzshell">
    <star-rating class="starz" style="display:block;" id="starz" :increment="increment" @rating-selected="setRating"></star-rating><input type="hidden" id="ratinghidden" name="rating" value="">
    </div>
    </div><script type="text/javascript">new Vue({el: "#starzshell",methods:{setRating:function(r){document.getElementById('ratinghidden').value=r;}},data:{increment:0.5}});</script>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
@endsection