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
                    <input type="text" class="form-control" id="fundraiser" name="fundraiser" placeholder="Booster" value="{{ old('fundraiser') }}" autocomplete="off">
                    <div id="suggestion-box" style="display: none;"></div>
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
    </div><script type="text/javascript">new Vue({el: "#starzshell",methods:{setRating:function(r){document.getElementById('ratinghidden').value=r;validate();}},data:{increment:1}});</script>
                <button type="submit" class="btn btn-default subtitle" id="createreview">Submit <span id="createglyph" style="display:none;" class="glyphicon glyphicon-ok"></span></button>
            </form>
        </div>
    </div>
    <script type="text/javascript">
$(document).ready(function(){
	$("#fundraiser").keyup(function(){
        if ($(this).val().length < 2) return;
		jQuery.ajax({
		type: "GET",
		url: "/autocomplete",
		data:'q='+$(this).val(),
		success: function(data){
			$("#suggestion-box").show();
			$("#suggestion-box").html(data);
		}
		});
	});
    $('.form-control').change(validate);
    $('.form-control').keyup(validate);
});
function validate() {
    var ok = true;
    $('.form-control').removeClass('alert-warning').each(function(i, e) {
        if ($(e).val().length < 2) {
            ok = false;
            if ($(e).val().length > 0) {
                $(e).addClass('alert-warning');
            }
        }
    });
    if (!$('#email').val().match(/^[^@]+@[^@]+$/)) {
        ok = false;
        $('#email').addClass('alert-warning');
    }
    if ($('#ratinghidden').val() <= 0) {
        ok = false;
    }
    if (ok) {
        $('#createreview').addClass('alert-success');
        $('#createglyph').show();
    } else {
        $('#createreview').removeClass('alert-success');
        $('#createglyph').hide();
    }

}
function selectFundraiser(val) {
  $("#fundraiser").val(val);
  $("#suggestion-box").hide();
}
    </script>
@endsection