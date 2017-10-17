<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public $count = 0, $sum = 0;

    static function allOrdered() {
        $all = Review::all()->shuffle();
        $ret = array();
        foreach ($all as $review) {
            if (empty($ret[$review->fundraiser])) {
                $ret[$review->fundraiser] = $review;
                $ret[$review->fundraiser]->count = 1;
                $ret[$review->fundraiser]->sum = $review->rating;
            } else {
                $ret[$review->fundraiser]->count++;
                $ret[$review->fundraiser]->sum += $review->rating;
            }
        }
        $collection = collect($ret)->map(function($r) {
            $r->rating = $r->sum / $r->count;
            return $r;
        });
        return $collection->sortBy('rating')->reverse();
    }
}
