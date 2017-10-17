@extends('layouts.app')

@section('content')
    <a class="btn btn-success subtitle m-b-md" href="/submit">Submit your own review! <span class="glyphicon glyphicon-arrow-right"></span></a>
      @if (!empty($reviews))
      <ul class="list-group">
      @foreach ($reviews as $review)
      <li class="list-group-item">
        <star-rating class="starz pull-left" id="review_<?php echo htmlspecialchars($review->id); ?>" :read-only="readonly" :rating="rating" :increment="increment"></star-rating>
        <div class="fundname pull-right"><?php echo htmlspecialchars($review->fundraiser); ?></div><div class="clearfix"></div>
      <div><?php echo htmlspecialchars($review->review); ?><br><small class="text-muted pull-right"><?php echo htmlspecialchars($review->name); ?></small>
@if ($review->count > 1)
    <br><small class="pull-right"><a href="/details/<?php echo htmlspecialchars($review->id); ?>">Read <?php echo ($review->count - 1); ?> other review<?php echo ($review->count == 2)?'':'s'; ?></a></small>
@endif
      <div class="clearfix"></div></div>
      </li> <script type="text/javascript">new Vue({el: "#review_<?php echo htmlspecialchars($review->id); ?>",data:{readonly:true,increment:0.01,rating:<?php echo sprintf('%01.1f', $review->rating);?>}});</script>
      @endforeach
      </ul>
      @else
      <div class="">No reviews entered yet!</div>
      @endif

@endsection